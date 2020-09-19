# PHP Cron tasks manager

This is a flexible tasks manager designed for MVC-type applications. It's used instead of standard linux *crontab* command.

The purpose of this tool is to provide an easy way to manipulate repetitive tasks. 

[Live Demo of original project by multimate](https://cron.multimate.ru)

[![License](http://img.shields.io/:license-mit-blue.svg)](http://doge.mit-license.org)

## How this works
Replace all tasks in crontab file with one which will invoke method ```TaskRunner::checkAndRunTasks()```.

Import tasks from current crontab file or add them manually. Active tasks will run one by one if current time matches with the task's time expression. Output of tasks can be handled. For each execution will be assigned status:
* **Success** if method returned ```true```; 
* **Error** if method returned ```false``` or an exception was caught; 
* **Started** if task is running or wasn't ended properly.

### Features
* Works with any storage engines
* Flexible implementation with interfaces
* Disable, enable and run tasks through tool interface
* Handle tasks output however you want
* Time expression helper shows next run dates
* Monitor runs results
* Export and import tasks from crontab
* Add needed method for new task from dropdown

## Installation

Install package via Composer
```
composer require weblogic/yii2-cron
```

### Requirements

* PHP 7.1 or above
* [dragonmantank/cron-expression](https://github.com/dragonmantank/cron-expression)

### Configure
* Create tables if you want to store data in database (use Yii migration)
* Implement `TaskInterface` and `TaskRunInterface` or use predefined classes from the Example folder
* Copy and modify controller and views. Or create your own.
* Import tasks through interface or add them manually
* Add new line into crontab file that will invoke ```TaskRunner::checkAndRunTasks()```
* Disable tasks that will be invoked through the manager
* Make sure that manager is not publicly available

## Screenshots

![Tasks list](https://cron.multimate.ru/img/Selection_006.png)

![Report](https://cron.multimate.ru/img/Selection_008.png)

![Logs](https://cron.multimate.ru/img/Selection_007.png)

![Import and export](https://cron.multimate.ru/img/Selection_003.png)
