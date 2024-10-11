let token = '';
let userId = '';
let currentGroupId = '';

// Register User
document.getElementById('register-btn').addEventListener('click', async () => {
    const username = document.getElementById('username').value;

    const response = await fetch('http://localhost:8000/users', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        credentials: 'include',
        body: JSON.stringify({ username }),
    });

    const data = await response.json();
    console.log(data);
    if (data.token) {
        console.log(data);
        token = data.token;
        userId = data.user_id;
        document.getElementById('token-display').innerText = `Registered with token: ${token}, User ID: ${userId}`;
    } else {
        console.log(data);
        alert(data.error);
    }
});

// Create Group
document.getElementById('create-group-btn').addEventListener('click', async () => {
    const groupName = document.getElementById('group-name').value;

    const response = await fetch('http://localhost:8000/groups', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        credentials: 'include',
        body: JSON.stringify({ name: groupName }),
    });

    const data = await response.json();
    if (data.id) {
        currentGroupId = data.id;
        document.getElementById('current-group').innerText = `Created group: ${groupName}`;
    } else {
        alert('Failed to create group.');
    }
});

// Join Group
document.getElementById('join-group-btn').addEventListener('click', async () => {
    console.log("joining the group . . .");
    const groupName = document.getElementById('group-name').value;
    console.log(groupName);

    try {
        const response = await fetch('http://localhost:8000/grouplists', {
            method: 'GET',
            credentials: 'include',
        });
        
        // Check for a successful response
        if (!response.ok) {
            console.error('Network response was not ok:', response.statusText);
            return;
        }

        const responseText = await response.text();
        const groupsArray = responseText.split('][');
        const groups = [];

        // Parse each JSON string
        groupsArray.forEach((groupStr, index) => {
            groupStr = groupStr.replace(/^\[|\]$/g, '');
            if (groupStr) {
                const parsedGroups = JSON.parse(`[${groupStr}]`);
                groups.push(...parsedGroups);
            }
        });

        console.log(groups);

        const group = groups.find(g => g.name === groupName);
        if (group) {
            currentGroupId = group.id;
            document.getElementById('current-group').innerText = `Joined group: ${group.name}`;
            loadMessages(currentGroupId);
        } else {
            alert('Group not found.');
        }
    } catch (error) {
        console.error('Fetch error:', error);
    }
});

// Send Message
document.getElementById('send-message-btn').addEventListener('click', async () => {
    const messageContent = document.getElementById('message-input').value;

    if (!currentGroupId) {
        alert('You must join a group before sending a message.');
        return;
    }

    try {
        console.log(token);
        console.log(userId);
        console.log(currentGroupId);
        // Actual POST request
        const response = await fetch(`http://localhost:8000/groups/${currentGroupId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            credentials: 'include',
            body: JSON.stringify({
                user_id: userId,
                message: messageContent,
            }),
        });
        const responseBody = await response.json();

        if (response.ok) {
            loadMessages(currentGroupId);
            document.getElementById('message-input').value = '';
        } else {
            alert('Failed to send message.');
            console.error('Error:', response.statusText);
        }
    } catch (error) {
        console.error('Failed to fetch:', error);
    }
});



// Load Messages for a Group
async function loadMessages(groupId) {
    const response = await fetch(`http://localhost:8000/groups/${groupId}/messageslist`, {
        method: 'GET',
        credentials: 'include',
    });

    if (response.ok) {
        const messages = await response.json();
        const messagesContainer = document.getElementById('messages-container');
        messagesContainer.innerHTML = '';

        messages.forEach(message => {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message');
            messageDiv.innerHTML = `<strong>${message.username}</strong>: ${message.message}`; // Correct key is `message.message`
            messagesContainer.appendChild(messageDiv);
        });
    } else {
        console.error('Failed to load messages:', response.statusText);
    }
}