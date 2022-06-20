<?php

declare(strict_types=1);

namespace Mijnkantoor\NMBRS\Traits;

use Mijnkantoor\NMBRS\Exceptions\NmbrsException;

trait EmployeeCallsTrait
{
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
            return $this->wrapArray($response->PersonalInfoContractSalaryAddress_GetAll_AllEmployeesByCompanyResult);
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
    
}
