<template>
    <div class="container">
        <table class="table-striped">
            <thead>
            <tr>
                <th>ADD </th>
                <th @click="getEvent()">
                    <input type="text" class="filter" placeholder="EVENT NAME" v-model="event_name">
                </th>
                <th>EVENT DATE</th>
                <th>EVENT CITY</th>
                <th>EVENT PARTICIPANTS</th>
            </tr>
            </thead>
            <tbody v-if="events">
            <tr v-for="event in events">
                <td @click="setParticipant(event.id)" data-toggle="modal" data-target="#modalLoginForm"><span class=" icon">➕</span></td>
                <td>{{ event.name }}</td>
                <td>{{ event.date}}</td>
                <td>{{ event.city}}</td>
                <td class="participant">
                    <table class="p_t" v-if="event.participant.length > 0">
                        <thead>
                        <tr>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Email</th>
                            <th>Ed</th>
                            <th>De</th>

                        </tr>

                        </thead>
                        <tbody v-for="p_t in event.participant">
                        <tr>
                            <td>{{p_t.first_name}}</td>
                            <td>{{p_t.last_name}}</td>
                            <td>{{p_t.email}}</td>
                            <td @click="getParticipant(event.id, p_t)" data-toggle="modal" data-target="#modalLoginForm"><span class=" icon">✎</span></td>
                            <td @click="deleteParticipant(event.id, p_t.id)"><span class=" icon">❌</span></td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Edit Participant</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                        <div class="md-form mb-1">
                            <span>First Name</span><br>
                            <input type="text"  class="form-control validate" v-model="participant.f_name">
                        </div>
                    </div>
                    <div class="modal-body mx-3">
                        <div class="md-form mb-1">
                            <span>Last Name</span><br>
                        <input type="text"  class="form-control validate" v-model="participant.l_name" >
                        </div>
                    </div>
                    <div class="modal-body mx-3">
                        <div class="md-form mb-1">
                            <span>Email</span><span v-if="invalid" style="color: red"> - exists already </span><br>
                        <input type="text"  class="form-control validate" :class="invalid ? existed : free"  v-model="participant.email">
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center" v-if="edit">
                        <button class="btn btn-default badge-info" data-dismiss="modal" @click="editParticipant(participant)">Save</button>
                    </div>
                    <div class="modal-footer d-flex justify-content-center" v-if="add">
                        <button class="btn btn-default badge-info" data-dismiss="modal" @click="addParticipant(participant)">Save</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                events: {},
                participant:{
                    event_id:'',
                    pt_id:'',
                    f_name:'',
                    l_name:'',
                    email: ''
                },
                add : false,
                edit: false,
                event_name:'',
                old_emails:[],
                invalid:false,
                existed:'red-border',
                free :'free_email'
            }
        },
        watch:{
            'event_name': function () {
                this.getEvents(this.event_name);
            },

            'participant.email':function () {
                this.invalid = this.old_emails.includes(this.participant.email) ?  true: false ;
            },

        },
        mounted() {
            this.getEvents();
        },
        methods: {
            getEvents(event_name = null) {
              return  axios.post('/api/get/events/', {event_name :event_name})
                    .then(response => {
                        this.events = response.data;
                        for(let i of  this.events){
                            for(let g of i.participant){
                                this.old_emails.push(g.email);
                            }

                        }
                        console.log(response.data)
                    })
                    .catch(error => console.log('axios error: ', error));
            },

            /**
             *
             * @param event_id
             * @param p_t_id
             * @returns {Promise<AxiosResponse<T> | never | void>}
             */
            deleteParticipant(event_id, p_t_id) {
                let id_s = {
                    event_id :event_id,
                    pt_id:p_t_id
                };
               return axios.post('/api/delete/participant/', id_s )
                   .then(response => {
                       console.log(response.data);
                       this.getEvents();
                   })
                   .catch(error => console.log('axios error: ', error));
            },


            getParticipant(event_id, p_t){
                this.edit = true;
                if(this.edit){
                    this.participant = {
                        event_id : event_id,
                        pt_id    : p_t.id,
                        f_name   : p_t.first_name,
                        l_name   : p_t.last_name,
                        email    : p_t.email
                    }
                }

            },

            /**
             *
             * @param participant
             * @returns {Promise<AxiosResponse<T> | never | void>}
             */
            editParticipant(participant){
                return axios.post('/api/edit/participant/', participant)
                    .then(response => {
                        console.log(response.data);
                        this.getEvents();
                    })
                    .catch(error => console.log('axios error: ', error));
            },

            setParticipant(event_id){
                this.add = true;
                this.participant.event_id = event_id;
          },

            addParticipant(participant){
                if(this.invalid){
                 return false
                }else {
                    return axios.post('/api/add/participant/', participant)
                        .then(response => {
                            console.log(response.data);
                            this.getEvents();
                        })
                        .catch(error => console.log('axios error: ', error));
                }


            },

        }
    }
</script>
<style scoped>

    .table-striped td, .table-striped th {
        border: 1px solid #ccc;
        padding: 5px 15px;
    }

    .table-striped td:nth-child(5) {
        padding: 0 !important;
        width: 560px;
    }

    .p_t {
        width: 100%;
    }

    .p_t td:nth-child(1),
    .p_t td:nth-child(2) {
        min-width: 130px !important;
        max-width: 130px !important;
        overflow-y: auto;
    }

    .p_t td:nth-child(3) {
        max-width: 200px !important;
        word-break: break-all;
        overflow-y: auto;
    }

    .p_t td:nth-child(4),
    .p_t td:nth-child(5)
    {
        text-align: center;
        width: 50px;
        vertical-align: center;
    }

    .participant td:nth-child(3) {
        overflow-y: auto;
    }

    .icon {
        display: inline-block;
        position: absolute;
        margin: auto;
        cursor: pointer;
    }
    .filter{
        outline: none;
        border: 1px rgba(9, 10, 56, 0.9) solid;
        padding: 3px 10px;
        border-radius: 8px;
        background: #ecedff;
    }
    .red-border {
     border: 2px solid red;
    }
    .free_email{
        border:1px solid blue;
    }
</style>
