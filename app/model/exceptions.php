<?php
namespace App\Model;

/**
 * Class exceptions
 * @package App\Model
 * @author Vladimír Antoš
 * @version 1.0
 */
class FinanceManagerException extends \Exception{

    public function __construct($message, $code = null, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class SystemException extends FinanceManagerException{
    public function __construct($message, $code = null, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class NotFoundException extends SystemException{
    public function __construct($message, $code = null, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class EntityExistsException extends SystemException{
    public function __construct($message, $code = null, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class ArgumentException extends SystemException{
    public function __construct($message, $code = null, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class PaymentsException extends SystemException{
    public function __construct($message, $code = null, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class MemberAccessException extends SystemException{
    public function __construct($message, $code = null, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}