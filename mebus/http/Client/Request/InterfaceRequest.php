<?php
/**
 * @author Serban Carlogea
 */

namespace mebus\http\Client\Request;

interface InterfaceRequest
{
    /**
     * @abstract
     *
     */
    function getName();

    /**
     * @abstract
     *
     */
    function getResource();

    /**
     * @abstract
     *
     */
    function execute();
}