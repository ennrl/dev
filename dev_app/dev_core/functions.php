<?php

function t($key) {
    return DevApp\Core\Translation::getInstance()->translate($key);
}
