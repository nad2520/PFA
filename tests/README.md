# Chatbot Test Suite

## Folder Structure

```text
tests/
|-- AI/
|   `-- ChatbotAiQualityTest.php
|-- Functional/
|   |-- ConversationCrudTest.php
|   `-- ResponseHandlingTest.php
|-- Performance/
|   `-- ChatbotPerformanceTest.php
|-- Security/
|   `-- ChatbotSecurityTest.php
`-- Support/
    |-- ChatbotTestCase.php
    |-- FakeLlmClient.php
    `-- FakeRecommendationProvider.php
```

## Install

1. Install Composer if it is not already available on your machine.
2. From `C:\xampp\htdocs\PFA`, run:

```powershell
composer install
```

This installs PHPUnit 10 and generates `vendor/autoload.php`.

## Run The Full Suite

```powershell
composer test
```

or:

```powershell
vendor\bin\phpunit --configuration phpunit.xml.dist
```

## Run A Single Category

```powershell
vendor\bin\phpunit --testsuite Functional
vendor\bin\phpunit --testsuite Security
vendor\bin\phpunit --testsuite AI
vendor\bin\phpunit --testsuite Performance
```

## Notes

- External LLM calls are stubbed with `Tests\Support\FakeLlmClient`, so the suite is deterministic and does not require network access.
- Recommendations are stubbed with `Tests\Support\FakeRecommendationProvider`.
- The domain layer under `app/services/chatbot/` is intentionally modular so the fake dependencies can be replaced later with a real repository and LLM adapter.
