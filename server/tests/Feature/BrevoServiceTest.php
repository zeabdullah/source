<?php

use App\Services\BrevoService;
use GuzzleHttp\Exception\RequestException;

test('brevo service can be instantiated', function () {
    $brevoService = new BrevoService();

    expect($brevoService)->toBeInstanceOf(BrevoService::class);
});

test('brevo service throws exception with invalid api key', function () {
    $brevoService = new BrevoService();

    expect(fn() => $brevoService->getTemplates('invalid-api-key'))
        ->toThrow(RequestException::class);
});

test('brevo service methods exist', function () {
    $brevoService = new BrevoService();

    expect(method_exists($brevoService, 'getTemplates'))->toBeTrue();
    expect(method_exists($brevoService, 'getTemplate'))->toBeTrue();
    expect(method_exists($brevoService, 'createTemplate'))->toBeTrue();
    expect(method_exists($brevoService, 'updateTemplate'))->toBeTrue();
    expect(method_exists($brevoService, 'deleteTemplate'))->toBeTrue();
    expect(method_exists($brevoService, 'sendTestTemplate'))->toBeTrue();
    expect(method_exists($brevoService, 'generateTemplatePreview'))->toBeTrue();
});
