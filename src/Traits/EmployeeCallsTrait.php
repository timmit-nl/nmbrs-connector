<?php

declare(strict_types=1);

namespace Mijnkantoor\NMBRS\Traits;

use Mijnkantoor\NMBRS\Exceptions\NmbrsException;

trait EmployeeCallsTrait
{
    /*
    |--------------------------------------------------------------------------
    | GETTERS
    |--------------------------------------------------------------------------
    |
    */

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
    public function getAllAddressEmployeesByCompany($company_id){
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
    public function getAllAbsenceEmployeesByCompany($company_id){
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
    public function getAllPersonalInfoEmployeesByCompany($company_id){
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
    public function getAllPersonalInfoContractSalaryAddressEmployeesByCompany($company_id){
        try {
            $response = $this->employeeClient->PersonalInfoContractSalaryAddress_GetAll_AllEmployeesByCompany(['CompanyID' => $company_id]);

            if (count($response->PersonalInfoContractSalaryAddress_GetAll_AllEmployeesByCompanyResult->PersonalInfoContractSalaryAddress) > 1) {
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
    public function getCurrentDepartmentByEmployee($employee_id){
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
    public function getCurrentPersonalInfoByEmployee($employee_id){
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
    public function getCurrentContractByEmployee($employee_id){
        try {
            $response = $this->employeeClient->Contract_GetCurrentPeriod(['EmployeeId' => $employee_id]);
            return $this->wrapArray($response->EmployeeContractItem->EmployeeContracts);
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
    public function getAllContractsByEmployee($employee_id){
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
    public function getLeaveBalance($employee_id){
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
    public function getCurrentLabourAgreementsByEmployee($employee_id){
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
    public function getAllLabourAgreementsByEmployee($employee_id){
        try {
            $response = $this->employeeClient->LabourAgreements_Get(['EmployeeId' => $employee_id]);
            return $this->wrapArray($response->LabourAgreements_GetResult);
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SETTERS
    |--------------------------------------------------------------------------
    |
    */

    /**
     * NL-only. Insert a absence with cause, this item will start from the given date in the object.
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=Absence2_Insert 
     * @todo determine if this is the correct method. (could also be Absence_insert.)
     * @param $employee_id
     * @return array
     * @throws NmbrsException
     */
    public function setAbsenceByMedicalFile($medicalFile){
        try {
            $response = $this->employeeClient->Absence2_Insert([
                'EmployeeId' => $medicalFile->employee->payroll_id,
                'NewDossier' => $medicalFile->tasks()->where('type', 'open_medicalFile')->first() ? false : true,
                'Absence' => [
                    'AbsenceId' => '',
                    // 'AbsenceId' => $medicalFile->payroll_id ?? null,
                    'Percentage' => $medicalFile->sick_percentage,
                    'Comment' => $medicalFile->particularities ?? null,
                    'Dossiernr' => $medicalFile->uuid,
                    'Dossier' => $medicalFile->type, //$medicalFile->started_at,
                    'Start' => displayDateTimeXsd($medicalFile->started_at), // '2022-06-21T10:20:44.000000Z',
                    'End' => isset($medicalFile->closed_at) ? displayDateTimeXsd($medicalFile->closed_at) :  null,
                    'RegistrationStartDate' => displayDateTimeXsd($medicalFile->date_of_execution), // '2022-06-21T10:20:44.000000Z',
                    'RegistrationEndDate' => isset($medicalFile->closed_at) ? displayDateTimeXsd($medicalFile->closed_at) :  null,
                    'AbsenceCause' => [
                        'CauseId' => 1,
                        'Cause' => $medicalFile->payrollVerzuimCause
                    ],
                ],
            ]);

            $medicalFile->update([
                'payroll_id' => $response->Absence2_InsertResult
            ]);

            return $this->wrapArray($response->Absence2_InsertResult);
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * NL-only. Insert a absence recovery message.
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=Absence_RecoveryInsert 
     * @todo determine if this is the correct method. (could also be Absence_insert.)
     * @param $employee_id
     * @return array
     * @throws NmbrsException
     */
    public function recoverAbsenceByMedicalFile($medicalFile){
        try {
            $response = $this->employeeClient->Absence_RecoveryInsert([
                'EmployeeId' => $medicalFile->employee->payroll_id,
                'AbsenceID' => $medicalFile->payroll_id,
                'Lastdayabsence' => displayDateTimeXsd($medicalFile->closed_at),
                'Reportdate' => displayDateTimeXsd($medicalFile->closed_at),
                'Comment' => $medicalFile->tasks()->where('name', 'Betermelding')->latest()->first()->description ?? null,
                
            ]);
            return $this->wrapArray($response->Absence_RecoveryInsertResult);
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }

    /**
     * NL-only. Insert a absence partial recovery message.
     * https://api.nmbrs.nl/soap/v3/EmployeeService.asmx?op=Absence2_Insert 
     * @todo determine if this is the correct method. (could also be Absence_insert.)
     * @param $employee_id
     * @return array
     * @throws NmbrsException
     */
    public function partialRecoverAbsenceByMedicalFile($medicalFile){

        try {
            $response = $this->employeeClient->Absence_PartialRecoveryInsert([
                'EmployeeId' => $medicalFile->employee->payroll_id,
                'AbsenceID' => $medicalFile->payroll_id,
                // 'StartDate' => end($medicalFile->course_absence_percentage)['start'],
                'StartDate' => displayDateTimeXsd($medicalFile->latest_course_absence_percentage['start']),
                'Reportdate' => displayDateTimeXsd($medicalFile->latest_course_absence_percentage['start']),
                'Percent' => $medicalFile->sick_percentage,
                'Comment' => null, 
            ]);
            return $this->wrapArray($response->Absence_PartialRecoveryInsertResult);
        } catch (\Exception $e) {
            throw new NmbrsException($e->getMessage());
        }
    }
}
