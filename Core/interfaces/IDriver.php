<?php

interface IDriver {
    static function getInstance();

    function fetch();

    function insert();

    function update();

    function delete();
}