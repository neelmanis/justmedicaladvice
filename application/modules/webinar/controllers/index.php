<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'vendor/autoload.php';

//authenticate with direct authentication
$client = new \Citrix\Authentication\Direct('xbXUkZwPGijZz9B3FeD2vlK6A8eT58M7');
print_r($client);exit;
$client->auth('sameer_dr@yahoo.co.in', 'Brother678');

//get upcoming weibnars
$goToWebinar = new \Citrix\GoToWebinar($client);
$webinars = $goToWebinar->getUpcoming();
print_r($webinars); exit;

//get info for a single webinar
//$webinar = reset($webinars);

//get registraion/join url
$registrationUrl = $webinar->getRegistrationUrl();

//get more info about a webinar
$webinarInfo = $goToWebinar->getWebinar('3941440615995009281');

//get registrants for given webinar
$registrants = $goToWebinar->getRegistrants('3941440615995009281');

//register a user for a webinar
$registrantData = array('email' => 'neel@kwebmaker.com', 'firstName' => 'Neel', 'lastName' => 'Gupta');
$registration = $goToWebinar->register('3941440615995009281',$registrantData);
//print_r($registration);exit;
//get past weibnars
$goToWebinar = new \Citrix\GoToWebinar($client);
$webinars = $goToWebinar->getPast();
print_r($webinars);


