<?php
declare(strict_types=1);

namespace Tests\Support;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use RuntimeException;

final class CoverageReportPage
{
    private DOMDocument $document;
    private DOMXPath $xpath;

    public function __construct(private readonly string $filePath)
    {
        if (!is_file($this->filePath)) {
            throw new RuntimeException(sprintf('Coverage report file not found: %s', $this->filePath));
        }

        $html = (string) file_get_contents($this->filePath);
        if ($html === '') {
            throw new RuntimeException(sprintf('Coverage report file is empty: %s', $this->filePath));
        }

        libxml_use_internal_errors(true);

        $this->document = new DOMDocument();
        $this->document->loadHTML($html);
        $this->xpath = new DOMXPath($this->document);
    }

    public function rawHtml(): string
    {
        return (string) file_get_contents($this->filePath);
    }

    public function filePath(): string
    {
        return $this->filePath;
    }

    public function baseDirectory(): string
    {
        return dirname($this->filePath);
    }

    public function htmlLanguage(): string
    {
        $html = $this->first('//html');

        return $html?->getAttribute('lang') ?? '';
    }

    public function title(): string
    {
        return trim($this->xpath->evaluate('string(//title)'));
    }

    /**
     * @return list<string>
     */
    public function stylesheetHrefs(): array
    {
        return $this->attributeValues('//link[@rel="stylesheet"]', 'href');
    }

    /**
     * @return list<string>
     */
    public function scriptSources(): array
    {
        return $this->attributeValues('//script[@src]', 'src');
    }

    /**
     * @return list<string>
     */
    public function anchorHrefs(): array
    {
        return $this->attributeValues('//a[@href]', 'href');
    }

    /**
     * @return list<string>
     */
    public function imageSources(): array
    {
        return $this->attributeValues('//img[@src]', 'src');
    }

    public function breadcrumbLabel(): string
    {
        return trim($this->xpath->evaluate('string(//nav[@aria-label="breadcrumb"])'));
    }

    public function breadcrumbCurrentItem(): string
    {
        return trim($this->xpath->evaluate('string(//ol[contains(@class, "breadcrumb")]/li[1])'));
    }

    /**
     * @return array{label:string, href:string}
     */
    public function dashboardBreadcrumb(): array
    {
        return [
            'label' => trim($this->xpath->evaluate('string(//ol[contains(@class, "breadcrumb")]/li[2]/a)')),
            'href' => trim($this->xpath->evaluate('string(//ol[contains(@class, "breadcrumb")]/li[2]/a/@href)')),
        ];
    }

    /**
     * @return list<array{
     *   name:string,
     *   href:string,
     *   cssClass:string,
     *   iconSource:string
     * }>
     */
    public function coverageRows(): array
    {
        $rows = [];
        /** @var DOMElement $row */
        foreach ($this->xpath->query('//tbody/tr[position() > 1]') as $row) {
            $anchor = $this->first('.//td[1]/a', $row);
            $image = $this->first('.//td[1]/img', $row);
            if ($anchor === null) {
                continue;
            }

            $firstCell = $this->first('./td[1]', $row);
            $rows[] = [
                'name' => trim($anchor->textContent),
                'href' => $anchor->getAttribute('href'),
                'cssClass' => $firstCell?->getAttribute('class') ?? '',
                'iconSource' => $image?->getAttribute('src') ?? '',
            ];
        }

        return $rows;
    }

    /**
     * @return list<array{
     *   class:string,
     *   ariaValueNow:string,
     *   style:string,
     *   srOnly:string
     * }>
     */
    public function progressBars(): array
    {
        $bars = [];
        /** @var DOMElement $bar */
        foreach ($this->xpath->query('//div[contains(@class, "progress-bar")]') as $bar) {
            $bars[] = [
                'class' => $bar->getAttribute('class'),
                'ariaValueNow' => $bar->getAttribute('aria-valuenow'),
                'style' => $bar->getAttribute('style'),
                'srOnly' => trim($this->xpath->evaluate('string(.//span[contains(@class, "sr-only")])', $bar)),
            ];
        }

        return $bars;
    }

    /**
     * @return list<string>
     */
    public function legendTexts(): array
    {
        $items = [];
        /** @var DOMElement $span */
        foreach ($this->xpath->query('//footer//p[1]/span') as $span) {
            $items[] = trim(preg_replace('/\s+/', ' ', $span->textContent) ?? '');
        }

        return $items;
    }

    /**
     * @return array{generatedBy:string, php:string, phpunit:string, fullText:string}
     */
    public function footerMetadata(): array
    {
        return [
            'generatedBy' => trim($this->xpath->evaluate('string(//footer//small/a[1])')),
            'php' => trim($this->xpath->evaluate('string(//footer//small/a[2])')),
            'phpunit' => trim($this->xpath->evaluate('string(//footer//small/a[3])')),
            'fullText' => trim(preg_replace('/\s+/', ' ', $this->xpath->evaluate('string(//footer//small)')) ?? ''),
        ];
    }

    /**
     * @return list<string>
     */
    public function localAssetReferences(): array
    {
        $references = [];
        foreach (array_merge($this->stylesheetHrefs(), $this->anchorHrefs(), $this->imageSources(), $this->scriptSources()) as $path) {
            if ($path === '' || preg_match('#^[a-z]+://#i', $path) === 1 || str_starts_with($path, '#')) {
                continue;
            }

            $references[] = $path;
        }

        return array_values(array_unique($references));
    }

    public function countNodes(string $xpath): int
    {
        return $this->query($xpath)->count();
    }

    public function evaluateString(string $xpath): string
    {
        return trim((string) $this->xpath->evaluate(sprintf('string(%s)', $xpath)));
    }

    public function query(string $xpath): DOMNodeList
    {
        return $this->xpath->query($xpath);
    }

    private function first(string $xpath, ?DOMElement $contextNode = null): ?DOMElement
    {
        $list = $this->xpath->query($xpath, $contextNode);
        $first = $list instanceof DOMNodeList ? $list->item(0) : null;

        return $first instanceof DOMElement ? $first : null;
    }

    /**
     * @return list<string>
     */
    private function attributeValues(string $xpath, string $attribute): array
    {
        $values = [];
        /** @var DOMElement $element */
        foreach ($this->xpath->query($xpath) as $element) {
            $values[] = $element->getAttribute($attribute);
        }

        return $values;
    }
}
