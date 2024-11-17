<?php
if (session_status() == PHP_SESSION_NONE) {
   session_start(); // Start the session only if it hasn't been started yet
}
