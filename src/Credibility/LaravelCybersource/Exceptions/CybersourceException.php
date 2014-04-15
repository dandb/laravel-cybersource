<?php

namespace Credibility\LaravelCybersource\Exceptions;

use Exception;

class CybersourceException extends Exception {}

class CybersourceConnectionException extends CybersourceException {}

class CybersourceMissingResponseCodeException extends CybersourceException {}

class CybersourceMissingDecisionException extends CybersourceException {}
//TODO: create more exceptions as needed
