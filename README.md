# Travel man proposal

This projects propose a possible solution to the travel man challenge requested by Zinio.

This has been a funny chanllenge to solve and maybe I get a little fired up and ended creating a project bigger than needed and with lots of complexity to demonstrate my capabilities almost at full potential... the truth is that I got lost trying to win the Nobel price by solving the p vs np problem.

Here you will find a project that runs over a docker container with a php-fpm to run the php code. At the devops folder you will find the files used to configure and create the architecture layer. As I planned to run only in a command line style I haven't added an Nginx service to enable a communication channel with the browser.

The chosed language to create the solution has been PHP, and the framework used has been Symfony at its latest version (ver 5.2.1 at the writing). It brings a little.... or to much... scaffolding to solve the challenge, but that brings me the opportunity to show the mastery of the tool.

As development methodologies I chosed to use TDD (to test the solution), with SOLID principles to create a reliable code, and the DDD practices to split the main code (the domain) from the framework and the infrastructure (database).

And last but not least, DDD practices are more of a set of guidelines than a set of rules, so my interpretation has been as follows (folder structure):
	
 - **project**: the whole project files
	 - **Zinio**: the folder with the solution code and with its own namespace to be framework agnostic.
		 - **Domain**: folder with the domain logic, agnostic to the framework and the project architecture.
			 - Subdomain (**Cities**, **Travelman**): folder to logicaly group the probles to solve
				 - **Application**: folder with the entrance gateway to our solution, the layer that connects our code to the framework
				 - **Exceptions**: folder with the personalized exception that our domain will face
				 - **Model**: folder with the needed entities declaration
				 - **Services**: here lays the logic of the solution, where the problems are solved. Also contains the repository interfaces, that way the code remains agnostic to the architecture
			 - **Infrastructure**: folder with the logic to connect to the outside world to save or retrieve the data (from database, files, in memory, api...).
			 - **Tests**: folder with the code to test all the created services. As we have seen the logic is on the service folder so we only test that, cause that is the meaning of unit tests. 

## Folder structure

Do not panic, I won't repeat the Zinio folder again, but I have to explain the surrounding structure and the modified files to personalise the solution:
- **devops**: folder containing the files to create the infratructure that will run the code, that means, the docker files and server configuration.
- **db**: folder with the files used as a storage system, here you will find the **cities.txt** file to modify it at desire.
- **project**: folder with all the code, it means the framework and the loved Zinio folder.
  - **config**: folder with the framework configurations
    - **services.yaml**: file with the autowiring configuration used by the framework to perform the dependency injections.
  - **src**: folder where the framework logic goes
    - **Command**: the gateway points from the outside, here starts the magic, or the pain. In its contained files arrive the user requests, in this case from the command line, and calls the domain code and builds the response to return to the user.
  - **Zinio**: here is.... I was joking, its been described before.

## Code execution

Ok, but, how to launch the project and start to find the shortest journey for our travel man?

First builds the docker images and install vendors by: 

    make build

The first time will take some time, I recommend to go to take a coffe. That command will download the needed docker images and build new ones.

Then you can run the dockers:

    make run

One service will be created:

 - **php_1**: the php_fpm service to execute the php code

With that command you will be able to see the system logs. To stop the service press

    ctrl + c

## Tests execution

 You can execute the project tests by the following command:

     make tests

If the docker image does not exists it will create it, so the command will take some time to finish.

At this point, the only thing left to do is **obtain the cities** in the best order to obtain the shortest path. To do that only its necessary to set the cities on the **db/cities.txt** file and run

    make travel-man

If the docker image does not exists it will create it, so the command will take some time to finish.

## The algorithm

The algorithm tries to find the shortest paht by checking the nearest nodes, but there is more, it tries to foresight in advance wich path will be the shortest. Depending in the ammount of nodes to check in advance the algorithm will go faster or no, its possible to adjust it adding a parameter to the previous command line as follows:

    make travel-man maxNodesToForesight=X

As you can see, it is possible to adjust the number of nodes to foresee, curiously the more does not means the better, in this case, the equilibrated one to the given cities list is set it by deffault at a value of 4, if you are bored I encourage you to try other numbers. During my testing I found that a **maxNodesToForesight=7** gives the best solution, but at a huge ammount of time. Don't worry, you wont need to do it, at the end of the document you will find the generated list with the default value and the best path.

The main file in charge to find the these solutions is **CalculatePathService** and it could we found at:

    project/Zinio/Domain/TravelMan/Services/CalculatePathService.php

And the file that launch the command is **TravelManCommand** and it could be found at:

    project/src/Command/TravelManCommand.php

## The possible solutions

### With default configuration:

 - Beijing
 - Tokyo
 - Bangkok
 - New Delhi
 - Jerusalem
 - Cairo
 - Casablanca
 - Dakar
 - Caracas
 - San Jose
 - Lima
 - Rio
 - Santiago
 - Accra
 - Lusaka
 - Singapore
 - Vladivostok
 - Astana
 - Moscow
 - Prague
 - Paris
 - London
 - New York
 - Toronto
- -Mexico City
 - San Francisco
- Vancouver
- Anchorage
- Reykjavík
- Perth
- Melbourne
- Auckland

### Best path found

- Beijing
- Tokyo
- Vladivostok
- Astana
- Moscow
- Prague
- Paris
- Casablanca
- Cairo
- Jerusalem
- New Delhi
- Bangkok
- Singapore
- Lusaka
- Accra
- Dakar
- Caracas
- Santiago
- Rio
- Lima
- San Jose
- Mexico City
- San Francisco
- Vancouver
- Toronto
- New York
- Anchorage
- Reykjavík
- London
- Perth
- Melbourne
- Auckland

