<?php

declare(strict_types=1);

namespace Mijnkantoor\NMBRS\Traits;

use App\Traits\ActivityLog;
use Mijnkantoor\NMBRS\Exceptions\NmbrsException;

trait EmployeeCallsTrait
{
    use ActivityLog;

    /**
     * Get functions for each employee in a given company
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=Function_GetAll_AllEmployeesByCompany_V2
     * @param $company_id
     * @return array
     * @throws NmbrsException
     */
    public function getFunctionAllEmployeesByCompany($company_id)
    {
        try {
            $response = $this->employeeClient->Function_GetAll_AllEmployeesByCompany_V2(['CompanyID' => $company_id]);
            return $this->wrapArray($response->Function_GetAll_AllEmployeesByCompany_V2Result);
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get the Address for each employee in a given company
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=Address_GetAll_AllEmployeesByCompany
     * @param $company_id
     * @return array
     * @throws NmbrsException
     */
    public function getAllAddressEmployeesByCompany($company_id)
    {
        try {
            $response = $this->employeeClient->Address_GetAll_AllEmployeesByCompany(['CompanyID' => $company_id]);
            return $this->wrapArray($response->Address_GetAll_AllEmployeesByCompanyResult);
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get all absences (ziek, zwangerschap etc) for all employees in a given company
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=Absence_GetAll_AllEmployeesByCompany
     * @param $company_id
     * @return array
     * @throws NmbrsException
     */
    public function getAllAbsenceEmployeesByCompany($company_id)
    {
        try {
            $response = $this->employeeClient->Absence_GetAll_AllEmployeesByCompany(['CompanyId' => $company_id]);
            return $this->wrapArray($response->Absence_GetAll_AllEmployeesByCompanyResult);
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get all personal info for all employees in a given company
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=PersonalInfo_GetAll_AllEmployeesByCompany
     * @param $company_id
     * @return array
     * @throws NmbrsException
     */
    public function getAllPersonalInfoEmployeesByCompany($company_id)
    {
        try {
            $response = $this->employeeClient->PersonalInfo_GetAll_AllEmployeesByCompany(['CompanyID' => $company_id]);
            return $this->wrapArray($response->PersonalInfo_GetAll_AllEmployeesByCompanyResult);
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get personal info + salary + contract + adres for all employees in a given company
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=PersonalInfoContractSalaryAddress_GetAll_AllEmployeesByCompany
     * @param $company_id
     * @return array
     * @throws NmbrsException
     */
    public function getAllPersonalInfoContractSalaryAddressEmployeesByCompany($company_id)
    {
        try {
            $response = $this->employeeClient->PersonalInfoContractSalaryAddress_GetAll_AllEmployeesByCompany(['CompanyID' => $company_id]);

            if (property_exists($response->PersonalInfoContractSalaryAddress_GetAll_AllEmployeesByCompanyResult, 'PersonalInfoContractSalaryAddress')) {
                return $response->PersonalInfoContractSalaryAddress_GetAll_AllEmployeesByCompanyResult->PersonalInfoContractSalaryAddress;
            }

            return $this->wrapArray($response->PersonalInfoContractSalaryAddress_GetAll_AllEmployeesByCompanyResult);
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get current active department by EmployeeID
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=Department_GetCurrent
     * @param $employee_id
     * @return array
     * @throws NmbrsException
     */
    public function getCurrentDepartmentByEmployee($employee_id)
    {
        try {
            $response = $this->employeeClient->Department_GetCurrent(['EmployeeId' => $employee_id]);
            return $this->wrapArray($response->Department_GetCurrentResult);
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get current active personal info by EmployeeID
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=PersonalInfo_GetCurrent
     * @param $employee_id
     * @return array
     * @throws NmbrsException
     */
    public function getCurrentPersonalInfoByEmployee($employee_id)
    {
        try {
            $response = $this->employeeClient->PersonalInfo_GetCurrent(['EmployeeId' => $employee_id]);
            return $this->wrapArray($response->PersonalInfo_GetCurrentResult);
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get active contracts for current period by EmployeeID
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=Contract_GetCurrentPeriod
     * @todo determine if this is the correct method. (could also be Contract_GetAll.)
     * @param $employee_id
     * @return array
     * @throws NmbrsException
     */
    public function getCurrentContractByEmployee($employee_id)
    {
        try {
            $response = $this->employeeClient->Contract_GetCurrentPeriod(['EmployeeId' => $employee_id]);

            if (! property_exists($response->EmployeeContractItem->EmployeeContracts, 'EmployeeContract')) {
                return $this->wrapArray((object) ['EmployeeContract' => []]);
            }

            foreach($response->EmployeeContractItem->EmployeeContracts->EmployeeContract as $key => $item) {
                if(is_string($key)) {
                    return $this->wrapArray($response->EmployeeContractItem->EmployeeContracts);
                } else {
                    return $this->wrapArray((object) ['EmployeeContract' => end($response->EmployeeContractItem->EmployeeContracts->EmployeeContract)]);
                }
            }

        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get All contracts for a given Employee
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=Contract_GetAll
     * @param $employee_id
     * @return array
     * @throws NmbrsException
     */
    public function getAllContractsByEmployee($employee_id)
    {
        try {
            $response = $this->employeeClient->Contract_GetAll(['EmployeeId' => $employee_id]);
            return $this->wrapArray($response->Contract_GetAllResult);
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get All contracts for a given Employee
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=LeaveBalance_Get
     * @param $employee_id
     * @return array
     * @throws NmbrsException
     */
    public function getLeaveBalance($employee_id)
    {
        try {
            $response = $this->employeeClient->LeaveBalance_Get(['EmployeeId' => $employee_id]);
            return $this->wrapArray($response->LeaveBalance_GetResult);
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get current labour agreement settings for current period by EmployeeID
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=LabourAgreements_GetCurrent
     * @todo determine if this is the correct method. (could also be LabourAgreements_Get.)
     * @param $employee_id
     * @return array
     * @throws NmbrsException
     */
    public function getCurrentLabourAgreementsByEmployee($employee_id)
    {
        try {
            $response = $this->employeeClient->LabourAgreements_GetCurrent(['EmployeeId' => $employee_id]);
            return $this->wrapArray($response->LabourAgreements_GetCurrentResult);
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get all labour agreement settings for current period by EmployeeID
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=LabourAgreements_Get
     * @param $employee_id
     * @return array
     * @throws NmbrsException
     */
    public function getAllLabourAgreementsByEmployee($employee_id)
    {
        try {
            $response = $this->employeeClient->LabourAgreements_Get(['EmployeeId' => $employee_id]);
            return $this->wrapArray($response->LabourAgreements_GetResult);
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get all employee types
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=EmployeeType_GetList
     * @return array
     * @throws NmbrsException
     */
    public function getListOfEmployeeTypes()
    {
        try {
            $response = $this->employeeClient->EmployeeType_GetList();
            return $this->wrapArray($response->EmployeeType_GetListResult);
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * NL-only. Insert a absence with cause, this item will start from the given date in the object.
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=Absence2_Insert
     * @todo determine if this is the correct method. (could also be Absence_insert.)
     * @param $employee_id
     * @return array
     * @throws NmbrsException
     */
    public function setAbsenceByNewMedicalFile($medicalFile)
    {

        $input = [
            'EmployeeId' => $medicalFile->employee->payroll_id,
            'NewDossier' => true,
            'Absence' => [
                'AbsenceId' => $medicalFile->id,
                // 'AbsenceId' => 123456789,
                'Comment' => $medicalFile->particularities ?? null,
                'Dossier' => $medicalFile->payrollVerzuimType,
                'Dossiernr' => $medicalFile->id,
                // 'Dossiernr' => 7654321,
                'End' => isset($medicalFile->closed_at) ? displayDateTimeXsd($medicalFile->closed_at) :  null,
                'Percentage' => $medicalFile->sick_percentage ?? '',
                'RegistrationEndDate' => isset($medicalFile->closed_at) ? displayDateTimeXsd($medicalFile->closed_at) :  null,
                'RegistrationStartDate' => displayDateTimeXsd($medicalFile->started_at), // '2022-06-21T10:20:44.000000Z',
                'Start' => displayDateTimeXsd($medicalFile->started_at), // '2022-06-21T10:20:44.000000Z',
            ],
        ];

        try {
            $response = $this->employeeClient->Absence_Insert($input);

            $medicalFile->update([
                'payroll_id' => $response->Absence_InsertResult
            ]);

            $return_array = [
                'status' => 'success',
                'message' => null,
                'title' => 'Verzuimmelding verstuurd naar NMBRS'
            ];
        } catch (\Exception $e) {
            $this->errorLog($e, 'Nmbrs', $medicalFile->employee, 'Fout bij versturen van verzuimmelding naar NMBRS', $input);

            $return_array = [
                'status' => 'failed',
                'message' => $e->getMessage(),
                'title' => 'Fout bij versturen van verzuimmelding naar NMBRS'
            ];
        }

        return $return_array;
    }

    /**
     * NL-only. Insert a absence with cause, this item will start from the given date in the object.
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=Absence2_Insert
     * @todo determine if this is the correct method. (could also be Absence_insert.)
     * @param $employee_id
     * @return array
     * @throws NmbrsException
     */
    public function setAbsenceByReopenedMedicalFile($medicalFile)
    {
        /**
         * info: NMBRS doesnt work with composed medicalFiles.
         * action: create a new one every time a medicalFiles reopens.
         */
        $dossiernr = $medicalFile->id . '000000' . $medicalFile->getVerzuimFrequency();
        $reopened_at = $medicalFile->tasks()->where('type', 'open_medicalFile')->latest()->first()->start_date ?? now();

        $input = [
            'EmployeeId' => $medicalFile->employee->payroll_id,
            // 'NewDossier' => $medicalFile->tasks()->where('type', 'open_medicalFile')->first() ? false : true,
            'NewDossier' => true,
            'Absence' => [
                // 'AbsenceId' => '',
                'AbsenceId' => (int) $dossiernr,
                'Comment' => $medicalFile->particularities ?? null,
                'Dossier' => $medicalFile->payrollVerzuimType,
                'Dossiernr' => (int) $dossiernr,
                // 'Dossiernr' => 7654321,
                'End' => isset($medicalFile->closed_at) ? displayDateTimeXsd($medicalFile->closed_at) :  null,
                'Percentage' => $medicalFile->sick_percentage ?? '',
                'RegistrationEndDate' => isset($medicalFile->closed_at) ? displayDateTimeXsd($medicalFile->closed_at) :  null,
                'RegistrationStartDate' => displayDateTimeXsd($reopened_at), // '2022-06-21T10:20:44.000000Z',
                'Start' => displayDateTimeXsd($reopened_at), // '2022-06-21T10:20:44.000000Z',
            ],
        ];

        try {
            $response = $this->employeeClient->Absence_Insert($input);

            $medicalFile->update([
                'payroll_id' => $response->Absence_InsertResult
            ]);

            $return_array = [
                'status' => 'success',
                'message' => null,
                'title' => 'Dossier heropend melding verstuurd naar NMBRS',
            ];
        } catch (\Exception $e) {
            $this->errorLog($e, 'Nmbrs', $medicalFile->employee, 'Fout bij versturen van dossier heropend melding naar NMBRS', $input);

            $return_array = [
                'status' => 'failed',
                'message' => $e->getMessage(),
                'title' => 'Fout bij versturen van dossier heropend melding naar NMBRS',
            ];
        }

        return $return_array;
    }

    /**
     * NL-only. Insert a absence recovery message.
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=Absence_RecoveryInsert
     * @todo determine if this is the correct method. (could also be Absence_insert.)
     * @param $employee_id
     * @return array
     * @throws NmbrsException
     */
    public function recoverAbsenceByMedicalFile($medicalFile)
    {
        $input = [
            'AbsenceID' => $medicalFile->payroll_id,
            'Comment' => $medicalFile->tasks()->where('name', 'Betermelding')->latest()->first()->description ?? null,
            'EmployeeId' => $medicalFile->employee->payroll_id,
            'Lastdayabsence' => displayDateTimeXsd($medicalFile->closed_at),
            'Reportdate' => displayDateTimeXsd($medicalFile->closed_at),
        ];

        try {
            $this->employeeClient->Absence_RecoveryInsert($input);

            $return_array = [
                'status' => 'success',
                'message' => null,
                'title' => 'Betermelding verstuurd naar NMBRS',
            ];
        } catch (\Exception $e) {
            $this->errorLog($e, 'Nmbrs', $medicalFile->employee, 'Fout bij versturen van betermelding naar NMBRS', $input);

            $return_array = [
                'status' => 'failed',
                'message' => $e->getMessage(),
                'title' => 'Fout bij versturen van betermelding naar NMBRS',
            ];
        }

        return $return_array;
    }

    /**
     * NL-only. Insert a absence partial recovery message.
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=Absence2_Insert
     * @todo determine if this is the correct method. (could also be Absence_insert.)
     * @param $employee_id
     * @return array
     * @throws NmbrsException
     */
    public function partialRecoverAbsenceByMedicalFile($medicalFile)
    {
        $input = [
            'AbsenceID' => $medicalFile->payroll_id,
            'Comment' => null,
            'EmployeeId' => $medicalFile->employee->payroll_id,
            'Percent' => $medicalFile->sick_percentage,
            'Reportdate' => displayDateTimeXsd($medicalFile->latest_course_absence_percentage['start']),
            'StartDate' => displayDateTimeXsd($medicalFile->latest_course_absence_percentage['start']),
        ];

        try {
            $this->employeeClient->Absence_PartialRecoveryInsert($input);

            $return_array = [
                'status' => 'success',
                'message' => null,
                'title' => 'Bijstelling dossier verstuurd naar NMBRS',
            ];
        } catch (\Exception $e) {
            $this->errorLog($e, 'Nmbrs', $medicalFile->employee, 'Fout bij versturen van bijstelling dossier naar NMBRS', $input);

            $return_array = [
                'status' => 'failed',
                'message' => $e->getMessage(),
                'title' => 'Fout bij versturen van bijstelling dossier naar NMBRS',
            ];
        }

        return $return_array;
    }

    /**
     * Contract_GetAll_AllEmployeesByCompany
     *
     */
    public function getAllContractsByCompany($company_id)
    {
        try {
            $result = $this->employeeClient->Contract_GetAll_AllEmployeesByCompany(['CompanyID' => $company_id]);
            return $result->Contract_GetAll_AllEmployeesByCompanyResult;
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Employment_GetAll_AllEmployeesByCompany
     *
     */
    public function getAllEmploymentsByCompany($company_id)
    {
        try {
            $result = $this->employeeClient->Employment_GetAll_AllEmployeesByCompany(['CompanyID' => $company_id]);
            return $result->Employment_GetAll_AllEmployeesByCompanyResult;
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get employments for one employee
     * @info: uses same call as getAllEmploymentsByCompany, but added some code to get employee specific
     */
    public function getAllEmploymentsByCompanyAndEmployee($company_id, $employee_id)
    {
        try {
            $employments = $this->getAllEmploymentsByCompany($company_id)->EmployeeEmploymentItem;

            foreach($employments as $employment) {
                if ($employee_id == $employment->EmployeeId) {
                    return $employment->EmployeeEmployments;
                }
            }

        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get latest employment for one employee
     * @info: uses same call as getAllEmploymentsByCompany, but added some code to get employee specific
     */
    public function getlatestEmploymentByCompanyAndEmployee($company_id, $employee_id)
    {
        try {
            $employments = $this->getAllEmploymentsByCompany($company_id)->EmployeeEmploymentItem;

            # if only one employment
            if(isset($employments->EmployeeId)) {
                if ($employee_id == $employments->EmployeeId) {

                    if (is_array($employments->EmployeeEmployments->Employment)) {
                        $data = [];
                        $data['Employment'] = $employments->EmployeeEmployments->Employment[0];
                        return $data;
                    }

                    return $employments->EmployeeEmployments;
                }
            }

            # multiple employments
            foreach($employments as $employment) {
                if ($employee_id == $employment->EmployeeId) {

                    if (is_array($employment->EmployeeEmployments->Employment)) {
                        $data = [];
                        $data['Employment'] = $employment->EmployeeEmployments->Employment[0];
                        return $data;
                    }

                    return $employment->EmployeeEmployments;
                }
            }

        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get list of absence types by employee
     *
     */
    public function getAbsenceList($employee_id)
    {
        try {
            $result = $this->employeeClient->Absence_GetList(['EmployeeId' => $employee_id]);

            return $result;

        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get list of wage tax settings by employee
     *
     */
    public function getWageTaxSettingsByEmployee($employee_id)
    {
        try {
            $result = $this->employeeClient->WageTax_Get([
                'EmployeeId' => $employee_id,
                'Period' => 1,
                'Year' => now()->format('Y'),
            ]);

            return $this->wrapArray($result->WageTax_GetResult);

        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * Get current function by employee
     *
     */
    public function getCurrentFunctionByEmployee($employee_id)
    {
        try {
            $result = $this->employeeClient->Function_GetCurrent(['EmployeeId' => $employee_id]);

            return $this->wrapArray($result->Function_GetCurrentResult);

        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }
}
