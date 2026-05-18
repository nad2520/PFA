<?php
declare(strict_types=1);

namespace Tests\UI;

use RuntimeException;
use PHPUnit\Framework\TestCase;
use Tests\Support\CoverageReportPage;

final class CoverageReportIndexHtmlEdgeCaseTest extends TestCase
{
    public function testParserRejectsMissingAndEmptyFixtureFiles(): void
    {
        $missingFile = dirname(__DIR__, 2) . '/tests/Fixtures/coverage-report/does-not-exist.html';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Coverage report file not found');

        new CoverageReportPage($missingFile);
    }

    public function testParserRejectsTrulyEmptyHtmlFiles(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'coverage-empty-');
        $this->assertNotFalse($tempFile);

        file_put_contents($tempFile, '');

        try {
            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage('Coverage report file is empty');

            new CoverageReportPage($tempFile);
        } finally {
            @unlink($tempFile);
        }
    }

    public function testMissingOrEmptyOptionalValuesFallBackGracefully(): void
    {
        $page = $this->fixture('missing-values-report.html');

        $this->assertSame('', $page->htmlLanguage());
        $this->assertSame('', $page->title());
        $this->assertSame('', $page->breadcrumbCurrentItem());
        $this->assertSame(['label' => '', 'href' => ''], $page->dashboardBreadcrumb());

        $this->assertSame(['', '_css/custom.css'], $page->stylesheetHrefs());
        $this->assertSame(['', 'https://phpunit.de/'], $page->anchorHrefs());
        $this->assertSame([''], $page->imageSources());
        $this->assertSame(['GhostModule'], array_column($page->coverageRows(), 'name'));
        $this->assertSame(['_css/custom.css'], $page->localAssetReferences());

        $metadata = $page->footerMetadata();
        $this->assertSame('', $metadata['generatedBy']);
        $this->assertSame('', $metadata['php']);
        $this->assertSame('', $metadata['phpunit']);
        $this->assertSame('', $metadata['fullText']);
    }

    public function testZeroCoverageAndNaValuesAreParsedAsValidRenderedStates(): void
    {
        $page = $this->fixture('zero-values-report.html');
        $bars = $page->progressBars();

        $this->assertSame('Zero Coverage Fixture', $page->title());
        $this->assertCount(4, $bars);
        $this->assertSame('0.00%', $page->evaluateString('//tbody/tr[1]/td[3]'));
        $this->assertSame('0 / 0', $this->normalizeWhitespace($page->evaluateString('//tbody/tr[1]/td[4]')));
        $this->assertSame('n/a', $page->evaluateString('//tbody/tr[1]/td[6]'));
        $this->assertSame('n/a', $page->evaluateString('//tbody/tr[1]/td[9]'));
        $this->assertSame('ZeroModule', $page->coverageRows()[0]['name']);
        $this->assertSame('danger', $page->coverageRows()[0]['cssClass']);

        foreach ($bars as $bar) {
            $this->assertSame('0.00', $bar['ariaValueNow']);
            $this->assertStringContainsString('width: 0.00%', $bar['style']);
            $this->assertStringContainsString('0.00% covered', $bar['srOnly']);
        }
    }

    public function testVeryLowCoverageValuesKeepTheirPrecisionForAccessibility(): void
    {
        $page = $this->fixture('tiny-values-report.html');
        $bars = $page->progressBars();

        $this->assertCount(2, $bars);
        $this->assertSame('0.01', $bars[0]['ariaValueNow']);
        $this->assertSame('width: 0.01%', $bars[0]['style']);
        $this->assertSame('0.01% covered (warning)', $bars[0]['srOnly']);
        $this->assertSame('0.10', $bars[1]['ariaValueNow']);
        $this->assertSame('width: 0.10%', $bars[1]['style']);
        $this->assertSame('0.10% covered (warning)', $bars[1]['srOnly']);
        $this->assertSame('1 / 10000', $this->normalizeWhitespace($page->evaluateString('//tbody/tr[1]/td[4]')));
        $this->assertSame('1 / 1000', $this->normalizeWhitespace($page->evaluateString('//tbody/tr[1]/td[7]')));
    }

    private function fixture(string $filename): CoverageReportPage
    {
        return new CoverageReportPage(
            dirname(__DIR__, 2) . '/tests/Fixtures/coverage-report/' . $filename
        );
    }

    private function normalizeWhitespace(string $value): string
    {
        $decoded = html_entity_decode($value, ENT_QUOTES | ENT_HTML5);
        $decoded = str_replace("\u{00A0}", ' ', $decoded);

        return trim(preg_replace('/[\s\p{Z}]+/u', ' ', $decoded) ?? '');
    }
}
