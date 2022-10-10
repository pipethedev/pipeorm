<?php

interface IDriver {
    function connect();

    function create();

    function read();

    function update();

    function delete();
}