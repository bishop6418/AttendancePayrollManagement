<template>
    <div >
        <v-card outlined elevation="2">
            <v-card-title>
                Schedules
            
                <v-btn
                    style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                    @click="dialogAdd = true"
                    title="add"
                >
                    <v-icon>mdi-plus-thick</v-icon>
                </v-btn>
                        
                <v-spacer></v-spacer>

                <!-- SEARCH BAR -->
                <v-col cols="12" sm="6" md="2">
                    <v-text-field
                        v-model="search"
                        label="Search"
                        single-line
                        hide-details
                    >
                        <v-icon
                            slot="append"
                        >
                            mdi-magnify
                        </v-icon>
                    </v-text-field>
                </v-col>
            </v-card-title>

            <v-container>
                <v-data-table
                    :headers="headers"
                    :items="schedules"
                    :search="search"
                    dark
                >
                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            small
                            @click.prevent="editSchedule(item)"
                            title="edit"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click.prevent="deleteSchedule(item)"
                            title="delete"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                </v-data-table>
            </v-container>

            <!-- ADD SCHEDULE DIALOG -->
            <validation-observer v-slot="{ handleSubmit }" ref="addScheduleObserver">
                <v-dialog v-model="dialogAdd" max-width="500px">
                    <v-card flat>
                        <v-container>
                            <v-card outlined elevation="2">
                                <v-container>
                                    <v-form @submit.prevent="handleSubmit(addSchedule)" ref="ref_form">
                                        <v-card-title class="headline">Add Schedule</v-card-title>
                                        <v-divider></v-divider>
                                        <v-card-text>
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12" md="12">
                                                        <validation-provider v-slot="{ errors }" name="Schedule Shift" rules="required">
                                                            <v-text-field
                                                                v-model="schedule.shift"
                                                                label="Schedule Shift"
                                                                :error-messages="errors"
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <v-dialog
                                                            ref="dialogTimeIn"
                                                            v-model="dialogTimeIn"
                                                            :return-value.sync="schedule.time_in"
                                                            persistent
                                                            width="290px"
                                                        >
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <validation-provider v-slot="{ errors }" name="Time In" rules="required">
                                                                <v-text-field
                                                                    v-model="schedule.time_in"
                                                                    label="Time In"
                                                                    readonly
                                                                    v-bind="attrs"
                                                                    v-on="on"
                                                                    :error-messages="errors"
                                                                ></v-text-field>
                                                                </validation-provider>
                                                            </template>
                                                            <v-time-picker
                                                                v-if="dialogTimeIn"
                                                                v-model="schedule.time_in"
                                                                dark
                                                            >
                                                                <v-spacer></v-spacer>
                                                                <v-btn text
                                                                    @click="$refs.dialogTimeIn.save(schedule.time_in)"
                                                                >
                                                                    OK
                                                                </v-btn>
                                                                <v-btn text
                                                                    @click="dialogTimeIn = false"
                                                                >
                                                                    Cancel
                                                                </v-btn>
                                                            </v-time-picker>
                                                        </v-dialog>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <v-dialog
                                                            ref="dialogTimeOut"
                                                            v-model="dialogTimeOut"
                                                            :return-value.sync="schedule.time_out"
                                                            persistent
                                                            width="290px"
                                                        >
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <validation-provider v-slot="{ errors }" name="Time Out" rules="required">
                                                                <v-text-field
                                                                    v-model="schedule.time_out"
                                                                    label="Time Out"
                                                                    readonly
                                                                    v-bind="attrs"
                                                                    v-on="on"
                                                                    :error-messages="errors"
                                                                ></v-text-field>
                                                                </validation-provider>
                                                            </template>
                                                            <v-time-picker
                                                                v-if="dialogTimeOut"
                                                                v-model="schedule.time_out"
                                                                dark
                                                            >
                                                                <v-spacer></v-spacer>
                                                                <v-btn text
                                                                    @click="$refs.dialogTimeOut.save(schedule.time_out)"
                                                                >
                                                                    OK
                                                                </v-btn>
                                                                <v-btn text
                                                                    @click="dialogTimeOut = false"
                                                                >
                                                                    Cancel
                                                                </v-btn>
                                                            </v-time-picker>
                                                        </v-dialog>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-card-text>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn small type="submit">Add</v-btn>
                                            <v-btn small @click="close">Cancel</v-btn>
                                        </v-card-actions>
                                    </v-form>
                                </v-container>
                            </v-card>
                        </v-container>
                    </v-card>
                </v-dialog>
            </validation-observer>

            <!-- EDIT SCHEDULE DIALOG -->
            <validation-observer v-slot="{ handleSubmit }" ref="updateScheduleObserver">
                <v-dialog v-model="dialogEdit" max-width="500px">
                    <v-card flat>
                        <v-container>
                            <v-card outlined elevation="2">
                                <v-container>
                                    <v-form @submit.prevent="handleSubmit(updateSchedule)" ref="ref_form">
                                        <v-card-title class="headline">Edit Schedule</v-card-title>
                                        <v-divider></v-divider>
                                        <v-card-text>
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12" md="12">
                                                        <validation-provider v-slot="{ errors }" name="Schedule Shift" rules="required">
                                                            <v-text-field
                                                                v-model="getSchedule.shift"
                                                                label="Schedule Shift"
                                                                :error-messages="errors"
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <v-dialog
                                                            ref="dialogTimeIn"
                                                            v-model="dialogTimeIn"
                                                            :return-value.sync="getSchedule.time_in"
                                                            persistent
                                                            width="290px"
                                                        >
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <validation-provider v-slot="{ errors }" name="Time In" rules="required">
                                                                <v-text-field
                                                                    v-model="getSchedule.time_in"
                                                                    label="Time In"
                                                                    readonly
                                                                    v-bind="attrs"
                                                                    v-on="on"
                                                                    :error-messages="errors"
                                                                ></v-text-field>
                                                                </validation-provider>
                                                            </template>
                                                            <v-time-picker
                                                                v-if="dialogTimeIn"
                                                                v-model="getSchedule.time_in"
                                                                dark
                                                            >
                                                                <v-spacer></v-spacer>
                                                                <v-btn text
                                                                    @click="$refs.dialogTimeIn.save(getSchedule.time_in)"
                                                                >
                                                                    OK
                                                                </v-btn>
                                                                <v-btn text
                                                                    @click="dialogTimeIn = false"
                                                                >
                                                                    Cancel
                                                                </v-btn>
                                                            </v-time-picker>
                                                        </v-dialog>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <v-dialog
                                                            ref="dialogTimeOut"
                                                            v-model="dialogTimeOut"
                                                            :return-value.sync="getSchedule.time_out"
                                                            persistent
                                                            width="290px"
                                                        >
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <validation-provider v-slot="{ errors }" name="Time Out" rules="required">
                                                                <v-text-field
                                                                    v-model="getSchedule.time_out"
                                                                    label="Time Out"
                                                                    readonly
                                                                    v-bind="attrs"
                                                                    v-on="on"
                                                                    :error-messages="errors"
                                                                ></v-text-field>
                                                                </validation-provider>
                                                            </template>
                                                            <v-time-picker
                                                                v-if="dialogTimeOut"
                                                                v-model="getSchedule.time_out"
                                                                dark
                                                            >
                                                                <v-spacer></v-spacer>
                                                                <v-btn text
                                                                    @click="$refs.dialogTimeOut.save(getSchedule.time_out)"
                                                                >
                                                                    OK
                                                                </v-btn>
                                                                <v-btn text
                                                                    @click="dialogTimeOut = false"
                                                                >
                                                                    Cancel
                                                                </v-btn>
                                                            </v-time-picker>
                                                        </v-dialog>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-card-text>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn small type="submit">Update</v-btn>
                                            <v-btn small @click="close">Cancel</v-btn>
                                        </v-card-actions>
                                    </v-form>
                                </v-container>
                            </v-card>
                        </v-container>
                    </v-card>
                </v-dialog>
            </validation-observer>

            <!-- DELETE SCHEDULE DIALOG -->
            <v-dialog v-model="dialogDelete" max-width="500px">
                <v-card flat>
                    <v-container>
                        <v-card outlined elevation="2">
                            <v-container>
                                <v-card-title class="headline justify-center">Are you sure to delete this schedule?</v-card-title>
                                <v-card-text>
                                    <v-container>
                                        <v-row class="text-center">
                                            <v-col cols="12" md="12">
                                                <p class="font-weight-bold"> {{ getSchedule.shift }} </p>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn small @click="deleteConfirm">Yes</v-btn>
                                    <v-btn small @click="close">Cancel</v-btn>
                                </v-card-actions>
                            </v-container>
                        </v-card>
                    </v-container>
                </v-card>
            </v-dialog>
        </v-card>
    </div>
</template>

<script>
    import axios from 'axios'

    import { ValidationObserver, ValidationProvider, extend, setInteractionMode } from 'vee-validate';
  	import { required, alpha_spaces, numeric } from 'vee-validate/dist/rules'
	    setInteractionMode('eager')

		extend('required', {
			...required,
			message: '{_field_} can not be empty'
		})

		extend('alpha_spaces', {
			...alpha_spaces,
			message: '{_field_} may only contain alphabetic characters as well as spaces'
		})

		extend('numeric', {
			...numeric,
			message: '{_field_} may only contain numeric characters',
		})

    export default {
        components: { ValidationObserver, ValidationProvider },
        data() {
            return {
                headers:
                [
                    { text: 'ID',       value: 'id', align: 'start' },
                    { text: 'SHIFT',    value: 'shift' },
                    { text: 'TIME IN',  value: 'time_in' },
                    { text: 'TIME OUT', value: 'time_out' },
                    { text: 'ACTIONS',  value: 'actions', sortable: false }
                ],
                schedules: [],
                schedule: {},
                getSchedule: {},

                // DIALOGS
                dialogAdd: false,
                dialogEdit: false,
                dialogDelete: false,
                dialogTimeIn: false,
                dialogTimeOut: false,

                search: '',
            }
        },

        mounted(){
            this.getSchedules();
        },

        methods: {
            close() {
                this.dialogAdd = false
                this.dialogEdit = false
                this.dialogDelete = false
            },

            getSchedules() {
				axios.get('/api/get-schedules').then(({ data }) => {
          			this.schedules = data;
        		})
			},

            reset() {
				this.$refs.ref_form.reset();
			},

            addSchedule() {
                this.$refs.addScheduleObserver.validate();
				axios.post('/api/add-schedule', this.schedule)
                    .then((res) => {
                        console.log(res);
                        alert('schedule added successfully');
					})
                this.getSchedules();
                this.close();
                this.reset();
			},

            editSchedule(item) {
                this.getSchedule = Object.assign({}, item)
                this.dialogEdit = true
            },

			updateSchedule() {
				axios.put('/api/update-schedule/' + this.getSchedule.id, {
                        shift:      this.getSchedule.shift,
                        time_in:    this.getSchedule.time_in,
                        time_out:   this.getSchedule.time_out
                    }).then((res) => {
                        console.log(res);
                        alert('schedule updated successfully');
                    })
                this.getSchedules();
                this.close();
			},

            deleteSchedule(item) {
                this.getSchedule = Object.assign({}, item)
                this.dialogDelete = true
            },

            deleteConfirm() {
				axios.delete('/api/delete-schedule/' + this.getSchedule.id)
                    .then((res) => {
                        console.log(res);
                        alert('schedule deleted successfully');
                    })
                this.getSchedules();
                this.close();
			}
        }
    }
</script>