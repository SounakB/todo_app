Run `php artisan migrate` to create the table in database

## Get Tasks

api endpoint - {{base_url}}/api/tasks?filter=&search= method : GET
Gets a list of pending tasks
filter is optional parameter which applied filter on due date it can have following values:
today, this_week, last_week, overdue
search is optional parameter which contains the string to search in title

## Create Task

api endpoint - {{base_url}}/api/task method : POST
The following fields are required to create new task:
title, description, due_date (in Y-m-d) format
parent_id is optional field, which contains the id of parent task in case of a subtask

## Show Task

api endpoint - {{base_url}}/api/task/{{id}} method : POST
Get details of the task and its subtasks

## Complete Task

api endpoint - {{base_url}}/api/task/complete/{{id}} method : GET
Marks the task and its subtasks as completed

## Delete

api endpoint - {{base_url}}/api/task/3 method : DELETE
Soft deletes the task and its subtask



