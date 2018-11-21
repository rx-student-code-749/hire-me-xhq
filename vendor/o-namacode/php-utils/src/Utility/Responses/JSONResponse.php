<?php
/**
 * Created by PhpStorm.
 * User: raphe
 * Date: 22/9/2018
 * Time: 2:59 PM
 */

namespace Namacode\Utility\Responses;


class JSONResponse
{
    const STATUS_OK = 200;
    const STATUS_CREATED = 201;
    const STATUS_NO_CONTENT = 204;
    const STATUS_FAILED = 400;

    private $status = self::STATUS_FAILED;
    private $error = false;
    private $errors = [];
    private $data = [];
    private $uniqueData = [];

    /**
     * @deprecated
     * @param string $msg
     */
    public function setErrorMsg(string $msg)
    {
        $this->setErrorMessage($msg);
    }
    /**
     * @param string $message
     */
    public function setErrorMessage(string $message)
    {
        $this->error = true;
        $this->status = self::STATUS_FAILED;
        $this->errors = $message;
    }
    /**
     * @param array $errs
     */
    public function setErrors(array $errs)
    {
        $this->error = true;
        $this->status = self::STATUS_FAILED;
        $this->errors = $errs;
    }
    /**
     * @deprecated
     * @param string $msg
     */
    public function addErrorMsg(string $msg)
    {
        $this->addErrorMessage($msg);
    }
    /**
     * @param string $message
     */
    public function addErrorMessage(string $message)
    {
        $this->error = true;
        $this->status = self::STATUS_FAILED;
        if (!is_array($this->errors))
            $this->errors = [$this->errors];
        $this->errors[] = $message;
    }
    /**
     * @deprecated
     * @param string $name
     * @param string $msg
     */
    public function addNamedErrorMsg(string $name, string $msg)
    {
        $this->addNamedErrorMessage($name, $msg);
    }
    /**
     * @param string $name
     * @param string $message
     */
    public function addNamedErrorMessage(string $name, string $message)
    {
        $this->error = true;
        $this->status = self::STATUS_FAILED;
        if (!is_null($this->errors))
            if (!is_array($this->errors))
                $this->errors = [$this->errors];
        $this->errors[$name] = $message;
    }
    /**
     * @return bool
     */
    public function hasError()
    {
        return $this->error ?: false;
    }
    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }
    /**
     * @param string $i
     * @param mixed $v
     */
    public function addData(string $i, $v)
    {
        $this->data[$i] = $v;
    }
    /**
     * @param string $i
     * @param mixed $v
     */
    public function addUnique(string $i, $v)
    {
        $this->uniqueData[$i] = $v;
    }
    /**
     * @param string $message
     * @param int $status_code
     */
    public function setSuccessMessage(string $message, $status_code = self::STATUS_OK)
    {
        $this->error = false;
        $this->status = $status_code;
        $this->addUnique('msg', $message);
    }
    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }
    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status ?: self::STATUS_FAILED;
    }
    /**
     * @return array
     */
    public function getResponse(): array
    {
        $response['status'] = $this->getStatus();

        if ($this->hasError())
            $response['errors'] = $this->errors;
        else
            if (!empty($this->data))
                $response['data'] = $this->data;

        if (!empty($this->uniqueData))
            foreach ($this->uniqueData as $k => $v)
                $response[$k] = $v;

        return $response;
    }
    public function respond()
    {
        echo json_encode($this->getResponse());
    }
}
