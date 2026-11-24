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
     * @return null|string
     *
     * @throws NmbrsException
     */
    public function getWageCodesByRunCompanyFilterByWageCode(int $company_id, int $run_id, int $year, int $wageCode): ?string
    {
        try {
            $response = $this->reportClient->Reports_GetWageCodesByRunCompany_FilterByWageCode_Background(['companyId' => $company_id, 'runId' => $run_id, 'year' => $year, 'wageCode' => $wageCode]);

            return $response->Reports_GetWageCodesByRunCompany_FilterByWageCode_BackgroundResult ?? null;
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * getWageCodesByRunCompany
     * https://api.nmbrs.nl/soap/v3/ReportService.asmx?op=Reports_GetWageCodesByRunCompany_Background
     *
     * @return string
     *
     * @throws NmbrsException
     */
    public function getWageCodesByRunCompany(int $company_id, int $run_id, int $year): ?string
    {
        try {
            $response = $this->reportClient->Reports_GetWageCodesByRunCompany_v2_Background(['companyId' => $company_id, 'runId' => $run_id, 'year' => $year]);

            return $response->Reports_GetWageCodesByRunCompany_v2_BackgroundResult ?? null;
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * https://api.nmbrs.nl/soap/v3/ReportService.asmx?op=Reports_BackgroundTask_Result
     *
     * @param string $taskId
     *
     * @return null|object
     *
     * @throws NmbrsException
     */
    public function backgroundTask(string $taskId): ?object
    {
        try {
            $response = $this->reportClient->Reports_BackgroundTask_Result(['TaskId' => $taskId]);

            return $response->Reports_BackgroundTask_ResultResult ?? null;
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Wait for the result of an Nmbrs background task using exponential backoff.
     * Polls with sleep sequence 3, 5, 8, 13, 20, 30, 30, ... seconds (capped at 30s per iteration)
     * and stops as soon as the next sleep would exceed maxSeconds.
     * Transient errors while polling are retried until maxConsecutiveErrors is reached;
     * unauthorized errors are thrown immediately.
     *
     * @param string $taskId
     * @param int $maxSeconds Total maximum wait time in seconds (default 60)
     * @param int $maxConsecutiveErrors Maximum number of consecutive failed polls before giving up (default 3)
     *
     * @return \stdClass The full result object with Status='Success'
     *
     * @throws NmbrsException On status Failed/Error/Unknown, timeout, unauthorized or too many consecutive errors
     */
    public function waitForBackgroundTaskResult(string $taskId, int $maxSeconds = 60, int $maxConsecutiveErrors = 3): object
    {
        $sleepSequence = [3, 5, 8, 13, 20, 30];
        $sleepCap = 30;
        $start = time();
        $iteration = 0;
        $lastStatus = '';
        $consecutiveErrors = 0;

        while (true) {
            try {
                $result = $this->backgroundTask($taskId);
                $consecutiveErrors = 0;
                $lastStatus = strtolower((string)($result?->Status ?? ''));
            } catch (NmbrsException $e) {
                $consecutiveErrors++;
                if ($e->isUnauthorizedError() || $consecutiveErrors >= $maxConsecutiveErrors) {
                    throw $e;
                }
                $result = null;
                $lastStatus = 'error while polling: ' . $e->getMessage();
            }

            if ('success' === $lastStatus) {
                return $result;
            }

            if (in_array($lastStatus, ['failed', 'error', 'unknown'], true)) {
                throw new NmbrsException(
                    'Background task ' . $taskId . ' failed: status=' . $lastStatus
                );
            }

            $elapsed = time() - $start;
            $sleep = min($sleepSequence[$iteration] ?? $sleepCap, $sleepCap);

            if (($elapsed + $sleep) > $maxSeconds) {
                $remaining = $maxSeconds - $elapsed;
                if ($remaining <= 0) {
                    break;
                }
                $sleep = min($sleep, $remaining);
            }

            sleep($sleep);
            $iteration++;
        }

        $elapsedFinal = time() - $start;
        if ('' !== $lastStatus) {
            $statusForMessage = $lastStatus;
        } else {
            $statusForMessage = 'unknown';
        }
        throw new NmbrsException(
            'Background task ' . $taskId . ' timeout after ' . $elapsedFinal . 's, last status: ' . $statusForMessage
        );
    }
}
