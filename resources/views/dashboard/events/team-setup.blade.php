<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Team Setup") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <div class="mb-4">
                    <h1 class="text-3xl text-gray-900 font-bold mb-4">{{ $event->name }}</h1>
                    <p>Create teams and manage which teams event members belong to.</p>
                    <p>
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded font-medium mt-4" id="add-team">Add Team</button>
                    </p>
                </div>

                <div class="grid grid-cols-2">
                    <div class="px-2">
                        <h2 class="text-3xl text-gray-900 font-bold mb-4">Teams</h2>
                        <div id="team-nodes">
                            <!-- Teams will be added here -->
                        </div>
                    </div>
                    <div>
                        <h2 class="text-3xl text-gray-900 font-bold mb-5">Members</h2>

                        <ul id="username-list">
                            @foreach ($users as $user)
                            <li draggable="true" id="user-{{ $user->id }}" class="user-listing">{{ $user->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Link back to event -->
                <p class="mt-4 p-2">
                    <a id="submit-teams" class="bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded font-medium w-full mt-5">
                        Submit Teams
                    </a>
                    <a href="{{ route('events.show', ['event' => $event]) }}" class="ml-2 text-black bg-gray-300 hover:bg-gray-400 px-4 py-3 rounded font-medium w-full mt-5">
                        Back to Event
                    </a>
                </p>

            </div>
        </div>
    </div>

    <div class="team-node bg-gray-300 p-3 mb-4 rounded border-2 border-gray-400 hidden" id="blank-team">
        <!-- Delete button that sits in the right top corner -->
        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded font-medium float-right droppable">X</button>

        <input type="text" name="team-name" id="team-name" class="mb-4 droppable" placeholder="Team Name" />

        <ul class="team-list" id="team-1">
            <li id="empty-member" class="droppable user-listing">No Members</li>
        </ul>
    </div>

    <script>
        // ====================================
        // 
        //              DRAGGABLE
        // 
        // ====================================

        let usernamesList = document.getElementById('username-list');
        let usernames = usernamesList.getElementsByTagName('li');

        function handleDragStart(event) {
            event.dataTransfer.setData('text/plain', event.target.id);
        }

        function handleDragEnter(event) {
            event.preventDefault();
        }

        function handleDragOver(event) {
            event.preventDefault();
        }

        function handleDrop(event) {
            event.preventDefault();
            const id = event.dataTransfer.getData('text/plain');
            const draggableElement = document.getElementById(id);
            const dropTarget = event.target;
            if (validDropTarget(dropTarget)) {
                attachUserToTeam(draggableElement, dropTarget)
            } else {
                console.log('Invalid drop target', dropTarget);
            }
        }

        function validDropTarget(target) {
            return target.classList.contains('team-list') ||
                target.classList.contains('droppable') ||
                target.classList.contains('team-node');
        }

        function attachUserToTeam(user, teamNode) {
            var list = null;
            if (teamNode.classList.contains('team-list')) {
                list = teamNode;
            } else if (teamNode.classList.contains('team-node')) {
                list = teamNode.getElementsByClassName('team-list')[0];
            } else if (teamNode.classList.contains('droppable')) {
                attachUserToTeam(user, teamNode.parentNode);
                return;
            } else {
                console.log(teamNode)
                return;
            }
            // if #empty-member exists, remove it
            const emptyMember = list.querySelector('#empty-member');
            if (emptyMember) {
                emptyMember.remove();
            }

            user.classList.add('droppable');
            list.appendChild(user);
        }

        // Add event listeners
        for (const username of usernames) {
            username.addEventListener('dragstart', handleDragStart);
        }

        function registerEventListeners(teamNode) {
            teamNode.addEventListener('dragenter', handleDragEnter);
            teamNode.addEventListener('dragover', handleDragOver);
            teamNode.addEventListener('drop', handleDrop);

            const deleteButton = teamNode.querySelector('button');
            deleteButton.addEventListener('click', function() {
                removeTeam(teamNode);
            });
        }

        // ====================================
        // 
        //             TEAM SETUP
        // 
        // ====================================

        const teamNode = document.getElementById('blank-team');
        const teamNodes = document.getElementById('team-nodes');
        const addTeamButton = document.getElementById('add-team');

        cloneTeam();

        addTeamButton.addEventListener('click', function() {
            cloneTeam();
        });

        function cloneTeam() {
            const newTeam = teamNode.cloneNode(true);
            newTeam.classList.remove('hidden');
            teamNodes.appendChild(newTeam);
            registerEventListeners(newTeam);
        }

        function removeTeam(teamNode) {
            // Move every user back to the usernames list
            const teamList = teamNode.getElementsByClassName('team-list')[0];
            const users = teamList.getElementsByTagName('li');
            // Store the length of the array since it will be changing during the loop
            const length = users.length;
            for (var x = 0; x < length; x++) {
                usernamesList.appendChild(users[0]);
            }

            // Remove the team node
            teamNode.remove();
        }

        // ====================================
        // 
        //             FORM SUBMIT
        // 
        // ====================================

        function serializeData() {
            const teams = document.getElementsByClassName('team-node');

            const teamFormData = [];

            for (const team of teams) {
                if (team.classList.contains('hidden')) {
                    continue;
                }

                const teamName = team.querySelector('#team-name').value;

                if (teamName == '') {
                    alert('Please make sure all teams have a name.');
                    return null;
                }

                const teamMembers = team.getElementsByClassName('user-listing');
                const teamMemberIds = [];
                for (const member of teamMembers) {
                    const id = member.id.split('-')[1];
                    teamMemberIds.push(id);
                }
                const teamData = {
                    name: teamName,
                    users: teamMemberIds
                };

                teamFormData.push(teamData);
            }

            return teamFormData;
        }

        function checkForEmptyTeams() {
            var usernames = usernamesList.getElementsByTagName('li');
            return usernames.length > 0;
        }

        function submitData(data) {
            $.ajax({
                url: "{{ route('events.teams.store', ['event' => $event]) }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    teams: data
                },
                success: function(response) {
                    // follow the redirect
                    window.location.href = response.redirect;
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        const submitButton = document.getElementById('submit-teams');
        submitButton.addEventListener('click', function() {
            if (checkForEmptyTeams()) {
                alert('Please make sure all users are in a team.');
                return;
            }

            var data = serializeData();

            if (data == null) {
                return;
            }

            submitData(data);
        });
    </script>
    <style>
        .user-listing {
            list-style-type: none;
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 5px;
            background-color: #f9f9f9;
            cursor: move;
        }
    </style>
</x-app-layout>