Run `php artisan migrate` to create the table in database

## Get Tasks

api endpoint - {{base_url}}/api/tasks?filter=&search= <br /> method : GET <br />
Gets a list of pending tasks <br />
filter is optional parameter which applied filter on due date it can have following values:
today, this_week, last_week, overdue <br />
search is optional parameter which contains the string to search in title

## Create Task

api endpoint - {{base_url}}/api/task <br /> method : POST <br />
The following fields are required to create new task:
title, description, due_date (in Y-m-d) format <br />
parent_id is optional field, which contains the id of parent task in case of a subtask

## Show Task

api endpoint - {{base_url}}/api/task/{{id}} <br /> method : GET <br />
Get details of the task and its subtasks

## Complete Task

api endpoint - {{base_url}}/api/task/complete/{{id}} <br /> method : GET <br />
Marks the task and its subtasks as completed

## Delete

api endpoint - {{base_url}}/api/task/3 <br /> method : DELETE <br />
Soft deletes the task and its subtask



