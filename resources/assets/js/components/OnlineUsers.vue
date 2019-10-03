<template>
    <div class="panel-body" style="width:100%;background: rgba(68, 71, 104, 0.7);">
        <div id="users_online" v-if="show" class="col-md-12">
            <dl v-for="user in users" :key="user.id">
                <div v-if="user.state==='online'" class="row">
                    <div class="col-md-12">
                        <ul class="user" v-if="user.state === 'online'">
                            <li>
                                <md-person-icon  style="vertical-align:middle;color:white;"/>
                            </li>
                            <li>
                                <dt><strong style="font-size:16px;">{{ user.name }}</strong></dt>
                            </li>
                            <li>
                                <md-radio-button-on-icon class="online" style="vertical-align:middle"/>
                            </li>
                        </ul>
                    </div>
                </div>
            </dl>
        </div>     
    </div>
</template>

<script>

import axios from 'axios';
import moment from 'moment';
import Chatkit from '@pusher/chatkit-client';

export default {
    name: "users",
    props: {
        roomId: String, 
        userId: String,
        userChatkit: Array,        
    },
    data() {
        return {            
            currentUser: null,
            users: [],
            show: null,
        }
    },
    methods: {
        fetchUserData(id){
            var room_u =    this.currentUser.users;
            for (var i = 0; i < room_u.length; i++) {
                if (room_u[i].id === id){
                    var result = room_u[i];
                }
            }
           return result;
        },
        async connectToChatkit(e) {
            const tokenProvider = new Chatkit.TokenProvider({
                url: `${process.env.MIX_APP_URL}/api/authenticate`,
            });
            const chatManager = new Chatkit.ChatManager({
                instanceLocator: process.env.MIX_CHATKIT_INSTANCE_LOCATOR,
                userId: this.userId,
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
                    onPresenceChanged: (state, user) => {

                        console.log(user.name + ' is ' + state.current);

                        var found = false;
                            if(this.users.length !== 0 && user){
                                for (let i = 0; i < this.users.length; i++) {
                                    if(this.users[i].id === user.id){
                                        found = true;
                                        break;                                       
                                    }
                                }                                                                                  
                            }
                        
                         if(state.current === 'online' && !found){
                             this.users.push({
                                'name': user.name,
                                'state': state.current,
                                'id': user.id,
                                'avatar': user.customData.avatar,
                            });
                        }else if (found){
                            for (let i = 0; i < this.users.length; i++) {
                                if (this.users[i].id === user.id){
                                    this.users[i].state = 'online';
                                }   
                            } 
                        }

                        if(state.current === 'offline'){ 
                            for (let i = 0; i < this.users.length; i++) {
                                if (this.users[i].id === user.id){
                                    this.users[i].state = 'offline';
                                }   
                            }    
                        }
                    }
                },
                messageLimit: 0
            })
        },
    },
    created () {      
        this.connectToChatkit(); 
        setTimeout(() => {
        }, 2000);
    },
};
</script>

