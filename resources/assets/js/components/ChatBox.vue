<template>
  <div class="panel-body" style="width:100%;background: rgba(68, 71, 104,.7);">
    <div id="chatbox"  class="col-md-12">
        <dl v-for="message in messages" :key="message.id">
            <ul v-if="message.text!='DELETED'">
                <li>
                    <dd><img :src="`/storage/${fetchUserData(message.senderId).avatar}`"></dd>
                </li>
                <li>
                    <dt><strong style="font-size:16px;">{{fetchUserData(message.senderId).name }}</strong></dt>
                    <dd>{{message.text}}</dd>
                    <dd style="color: #DEDEDE;"><md-time-icon style="vertical-align:middle;"/>&nbsp;{{ formatTime(message.timestamp) }}</dd>
                    <br>
                </li>
            </ul>
        </dl>
            
    </div>   
    <div id="send_form" class="col-md-12" style="background: rgba(68, 71, 104, 0.001);">
        <div class="input-group" style="width:75%;margin-left: -80px;">
            <input type="text" v-model="message" @keyup.enter="sendMessage" class="form-control" placeholder="Type your message..." autofocus>
        </div>
        <div class="input-group" style="width:15%;position:absolute;top:50%;transform: translateY(-50%);">
            <button @click="sendMessage" class="btn btn-primary">Send</button>
        </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import Chatkit from '@pusher/chatkit-client';

export default {
    name: "chatbox",
    props: {
        roomId: String, 
        userId: String,
        initialMessages: Array,
        roomUsers: Array,        
    },
    data() {
        return {            
            currentUser: null,
            message: '',
            messages: this.initialMessages.reverse(),
            users: null,
            show:null,
        }
    },
    methods: {
        fetchUserData(id){
            var room_u =    this.roomUsers;
            for (var i = 0; i < room_u.length; i++) {
                if (room_u[i].email === id){
                    var result = room_u[i];
                }
            }
           return result;
        },
        async connectToChatkit(e) {
            //url: `${process.env.MIX_APP_URL}/api/authenticate`,
            const tokenProvider = new Chatkit.TokenProvider({
                url: `https://fivedemo.herokuapp.com/api/authenticate`,
            });
            const chatManager = new Chatkit.ChatManager({
                instanceLocator: process.env.MIX_CHATKIT_INSTANCE_LOCATOR,
                userId: this.userId,
                connectionTimeout: 40000,
                tokenProvider,
            });
            chatManager.connect(e)
                .then(user => {
                    this.currentUser = user;
                    this.show = true;
                    this.subscribeToRoom();

                     
                    
                })
                .catch(error => {
                        console.log('Error on connection', error)
                })
        },
        subscribeToRoom(e) {
            this.currentUser.subscribeToRoomMultipart({
                roomId: this.roomId,
                hooks: {
                    onMessage: message => {
                        this.messages.push({
                            id: message.id,
                            senderId: message.senderId,
                            text: message['parts'][0]['payload']['content'],
                            timestamp: message.createdAt,
                        })
                    },
                    onUserJoined: async user => {
                        await this.getUsers()
                        this.messages.push({
                            text: `${user.name} joined ${this.formatTime(user.created_at)}`
                        })
                    },
                },
                messageLimit: 0
            })
        },
        getUsers(e) {
            // axios.get(`${process.env.MIX_APP_URL}/api/users`)
            axios.get(`https://fivedemo.herokuapp.com/api/users`)
                .then(res => {
                    this.users = res['data']['body']
                });
            console.log(this.users);
        },
        sendMessage(e) {
            //axios.post( `${process.env.MIX_APP_URL}/api/message`
            if (this.message.trim() === '') return;
            axios.post( `https://fivedemo.herokuapp.com/api/message`, {
                user: this.userId,
                message: this.message,
            })
            .then(message => {
                this.message = ''
            });
        },
        findSender(senderId){
            const sender = this.users.find(user => senderId == user.id);
            return sender
        },
        formatTime(timestamp) {
           return moment(timestamp).fromNow();
        },
    },
    created () {      
        this.getUsers();
        this.connectToChatkit(); 
    },
};
</script>
<style>
dl{
    margin-bottom:0px;
}

#chatbox {
    text-align: left;
    height: 505px;
    overflow-y: scroll;
}

#users{
    
    text-align: left;
    max-height: 400px;
    overflow-y: scroll;
}

#users dd strong{
    padding-left: 20px!important;;
}

#users .online_user:nth-last-child() dd hr{
    display: none;
}

.online_user dl,.online_user dl dd hr{
    margin:5px 0 5px 0px!important;
}
.offline{
    width:15px;
    height:15px;
    border-radius:50%;
    color:#d33;
    vertical-align:middle;
    margin-left:5px;
}


dl ul li{
    display:inline-block;
}

dl ul li dd img {
    width: 50px;
    top: 50%;
    /* position: absolute; */
    transform: translateY(-50%);
    margin-right: 6px;
    border-radius: 50%;
}

#chatbox dl ul{
    border-bottom:1px solid white;
}

</style>