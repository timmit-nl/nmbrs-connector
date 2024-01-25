<?php

declare(strict_types=1);

namespace Mijnkantoor\NMBRS;

use Mijnkantoor\NMBRS\Traits\CompanyCallsTrait;
use Mijnkantoor\NMBRS\Traits\DebtorCallsTrait;
use Mijnkantoor\NMBRS\Traits\EmployeeCallsTrait;
use Mijnkantoor\NMBRS\Traits\ReportCallsTrait;
use SoapClient;
use SoapHeader;

class NmbrsClient
{
    const DEBTOR_SERVICE = 'DebtorService';
    const COMPANY_SERVICE = 'CompanyService';
    const EMPLOYEE_SERVICE = 'EmployeeService';
    const REPORT_SERVICE = 'ReportService';

    const DeclarationPeriodMonth = '1';
    const DeclarationPeriodFourWeek = '2';
    const DeclarationPeriodWeek = '3';

    protected SoapClient $debtorClient;
    protected SoapClient $companyClient;
    protected SoapClient $employeeClient;
    protected SoapClient $reportClient;

    /**
     * @var false
     */
    private $sandbox;

    use CompanyCallsTrait;
    use DebtorCallsTrait;
    use EmployeeCallsTrait;
    use ReportCallsTrait;

    public function __construct(string $username, string $password, string $domain, bool $sandbox = false)
    {
        $this->sandbox = $sandbox;

        $this->debtorClient = $this->getClientForService(self::DEBTOR_SERVICE, $username, $password, $domain);
        $this->companyClient = $this->getClientForService(self::COMPANY_SERVICE, $username, $password, $domain);
        $this->employeeClient = $this->getClientForService(self::EMPLOYEE_SERVICE, $username, $password, $domain);
        $this->reportClient = $this->getClientForService(self::REPORT_SERVICE, $username, $password, $domain);
    }

    protected function getBaseUrl(): string
    {
        if ($this->sandbox) {
            $url = "https://api-sandbox.nmbrs.nl/soap/v3/";
        } else {
            $url = "https://api.nmbrs.nl/soap/v3/";
        }
        return $url;
    }

    protected function getClientForService(string $service, string $username, string $password, string $domain): SoapClient
    {
        $ns = $this->getBaseUrl() . $service;

        $client = new SoapClient($ns . ".asmx?WSDL", ['trace' => 1]);

        $authHeader = new SoapHeader($ns, "AuthHeaderWithDomain", [
            'Username' => $username,
            'Token' => $password,
            'Domain' => $domain,
        ]);
        $client->__setSoapheaders([$authHeader]);

        return $client;
    }

    private function wrapArray(mixed $data): array
    {
        if ($data == null) {
            return [];
        }

        if (is_array($data)) { // array of objects
            return $data;
        }

        return [$data];
    }
}
