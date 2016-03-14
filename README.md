# Merlin
Merlin, BotNet as a service

## Installation

To use merlin the PHP application host will have to have docker and docker-compose installed.
Also the user used by the PHP server will have to be a part of the docker group to have sufficient permission to spin up containers.
Before any other operation can be done you need to run the load file under /src/txt to load the sets for uuid generation on the redis database.
