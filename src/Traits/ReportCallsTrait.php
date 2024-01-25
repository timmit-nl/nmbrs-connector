<?php

declare(strict_types=1);

namespace Mijnkantoor\NMBRS\Traits;

use Mijnkantoor\NMBRS\Exceptions\NmbrsException;

trait ReportCallsTrait
{

    /**
     * getWageCodesByRunCompany
     * https://api.nmbrs.nl/soap/v3/ReportService.asmx?op=Reports_GetWageCodesByRunCompany_FilterByWageCode_Background
     *
     * @return mixed
     * @throws NmbrsException
     */
    public function getWageCodesByRunCompany(int $company_id, int $run_id, int $year, int $wageCode): ?string
    {
        try {
            $response = $this->reportClient->Reports_GetWageCodesByRunCompany_FilterByWageCode_Background(['companyId' => $company_id, 'runId' => $run_id, 'year' => $year, 'wageCode' => $wageCode]);

            return $response->Reports_GetWageCodesByRunCompany_FilterByWageCode_BackgroundResult ?? null;
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * https://api.nmbrs.nl/soap/v3/ReportService.asmx?op=Reports_BackgroundTask_Result
     *
     * @param string $taskId
     *
     * @return object
     *
     * @throws NmbrsException
     */
    public function backgroundTask(string $taskId): object
    {
        try {
            $response = $this->reportClient->Reports_BackgroundTask_Result(['TaskId' => $taskId]);

            return $response->Reports_BackgroundTask_ResultResult ?? null;
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }
}
