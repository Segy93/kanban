1. install oracle virtualbox https://www.virtualbox.org/wiki/Downloads
2. install vagrant https://www.vagrantup.com/
3. download https://drive.google.com/file/d/1ZDhcd2Cvxz0DpXT8dRhzRcrl4EbtWFQ_/view?usp=sharing
4. extract to desired location
5. make folder kanban in the same location
6. open terminal
7. go to the path of Vagrantfile that was previously extracted
8. vagrant up
9. after installation finishes vagrant ssh
10.
```
sudo -i
cd /tmp/install
./install.sh
```


PS. Shell script is for installing lamp stack and cloning github project











Project thoughts

The project contains migrations for users, tickets and ticket history, crud endpoints for users, tickets as well as search and authentication route. Ticket history is created each time before update. Since middleware for protection isn't specified I decided to use Laravel Auth because it is integrated in framework. The project functions the same as github projects where users can assign and manage tickets, however users are not permitted to delete other users and change each others data.

There is also room for expansion. For example status table can be added as well as crud endpoints for it so there can be more than 3 lanes or less. Also some sort of roles and permissions for users where users can have permissions for certain tasks, but are not permitted to do others. There can also be many to many relationship between users and tickets so more users can be added to one task, etc.
