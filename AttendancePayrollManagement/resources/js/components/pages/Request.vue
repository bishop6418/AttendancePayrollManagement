<template>
    <div >
        <v-card outlined elevation="2">
            <v-card-title>
                LEAVE REQUEST

                <v-btn
                    style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                    @click="dialogAdd = true"
                    title="leave request"
                >
                    <v-icon>mdi-plus-thick</v-icon>
                </v-btn>
                    <!-- CASH ADVANCE -->
                    <v-btn
                        style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                        @click = "cash_advance"
                        title="cash advance"
                    >
                        <v-icon>mdi-cash-plus</v-icon>
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
                    :items="leaves"
                    :search="search"
                    dark
                >
                    <template v-slot:item.from="{ item }">
                        {{ moment(item.from).format("MMMM D, YYYY") }}
                    </template>

                    <template v-slot:item.to="{ item }">
                        {{ moment(item.to).format("MMMM D, YYYY") }}
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            small
                            @click.prevent="editLeave(item)"
                            title="edit"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click.prevent="deleteLeave(item)"
                            title="delete"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                </v-data-table>
            </v-container>

            <!-- ADD POSITION DIALOG -->
            <validation-observer v-slot="{ handleSubmit }" ref="addPositionObserver">
                <v-dialog v-model="dialogAdd" max-width="500px">
                    <v-card flat>
                        <v-container>
                            <v-card outlined elevation="2">
                                <v-container>
                                    <v-form @submit.prevent="handleSubmit(addLeave)" ref="ref_form">
                                        <v-card-title class="headline">Add Leave</v-card-title>
                                        <v-divider></v-divider>
                                        <v-card-text>
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" name="Reason" rules="required">
                                                            <v-text-field
                                                                v-model="leave.description"
                                                                label="Reason"
                                                                :error-messages="errors"
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                     <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" name="Employee Name" rules="required">
                                                             <v-autocomplete
                                                                v-model="leave.employee"
                                                                :items="employees"
                                                                :error-messages="errors"
                                                                item-value="'id'"
                                                                dense
                                                                filled
                                                                label="Enter Employee Name">
                                                            <template slot="selection" slot-scope="data">
                                                                {{ data.item.first_name }} {{ data.item.last_name }}
                                                            </template>
                                                            <template slot="item" slot-scope="data">
                                                                {{ data.item.first_name }} {{ data.item.last_name }}
                                                            </template>
                                                            </v-autocomplete>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" name="Start Date" rules="required">
                                                            <v-menu
                                                                ref="menu"
                                                                v-model="menu"
                                                                :close-on-content-click="false"
                                                                :return-value.sync="date"
                                                                transition="scale-transition"
                                                                offset-y
                                                                min-width="auto"
                                                            >
                                                                <template v-slot:activator="{ on, attrs }">
                                                                <v-text-field
                                                                    v-model="leave.from"
                                                                    label="Start date"
                                                                    prepend-icon="mdi-calendar"
                                                                    readonly
                                                                    v-bind="attrs"
                                                                    v-on="on"
                                                                    :error-messages="errors"
                                                                ></v-text-field>
                                                                </template>
                                                                <v-date-picker
                                                                v-model="leave.from"
                                                                no-title
                                                                scrollable
                                                                >
                                                                <v-spacer></v-spacer>
                                                                <v-btn
                                                                    text
                                                                    color="primary"
                                                                    @click="menu = false"
                                                                >
                                                                    Cancel
                                                                </v-btn>
                                                                <v-btn
                                                                    text
                                                                    color="primary"
                                                                    @click="$refs.menu.save(date)"
                                                                >
                                                                    OK
                                                                </v-btn>
                                                                </v-date-picker>
                                                            </v-menu>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" name="End Date" rules="required">
                                                            <v-menu
                                                                ref="menu2"
                                                                v-model="menu2"
                                                                :close-on-content-click="false"
                                                                :return-value.sync="date"
                                                                transition="scale-transition"
                                                                offset-y
                                                                min-width="auto"
                                                            >
                                                                <template v-slot:activator="{ on, attrs }">
                                                                <v-text-field
                                                                    v-model="leave.to"
                                                                    label="End date"
                                                                    prepend-icon="mdi-calendar"
                                                                    readonly
                                                                    v-bind="attrs"
                                                                    v-on="on"
                                                                    :error-messages="errors"
                                                                ></v-text-field>
                                                                </template>
                                                                <v-date-picker
                                                                v-model="leave.to"
                                                                no-title
                                                                scrollable
                                                                >
                                                                <v-spacer></v-spacer>
                                                                <v-btn
                                                                    text
                                                                    color="primary"
                                                                    @click="menu2= false"
                                                                >
                                                                    Cancel
                                                                </v-btn>
                                                                <v-btn
                                                                    text
                                                                    color="primary"
                                                                    @click="$refs.menu2.save(date)"
                                                                >
                                                                    OK
                                                                </v-btn>
                                                                </v-date-picker>
                                                            </v-menu>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" name="Status" rules="required">
                                                            <v-select
                                                            :items="status"
                                                            label="Status"
                                                            :error-messages="errors"
                                                            v-model="leave.status"
                                                            ></v-select>

                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" name="Status" rules="required">
                                                            <v-select
                                                            :items="day"
                                                            label="Day"
                                                            :error-messages="errors"
                                                            v-model="leave.day"
                                                            ></v-select>

                                                        </validation-provider>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-card-text>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn small type="submit"> Add </v-btn>
                                            <v-btn small @click="close">Cancel</v-btn>
                                        </v-card-actions>
                                    </v-form>
                                </v-container>
                            </v-card>
                        </v-container>
                    </v-card>
                </v-dialog>
            </validation-observer>

            <!-- EDIT LEAVE DIALOG -->
            <validation-observer v-slot="{ handleSubmit }" ref="updatePositionObserver">
                <v-dialog v-model="dialogEdit" max-width="500px">
                    <v-card flat>
                        <v-container>
                            <v-card outlined elevation="2">
                                <v-container>
                                    <v-form @submit.prevent="handleSubmit(updatePosition)" ref="ref_form">
                                        <v-card-title class="headline">Edit Leave Request</v-card-title>
                                        <v-divider></v-divider>
                                        <v-card-text>
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" name="Reason" rules="required">
                                                             <v-text-field
                                                                v-model="getLeave.description"
                                                                label="Reason"
                                                                :error-messages="errors"
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" name="Reason" rules="required">
                                                            <v-menu
                                                                ref="menu"
                                                                v-model="menu"
                                                                :close-on-content-click="false"
                                                                :return-value.sync="date"
                                                                transition="scale-transition"
                                                                offset-y
                                                                min-width="auto"
                                                            >
                                                                <template v-slot:activator="{ on, attrs }">
                                                                <v-text-field
                                                                    v-model="getLeave.from"
                                                                    label="Start date"
                                                                    prepend-icon="mdi-calendar"
                                                                    readonly
                                                                    v-bind="attrs"
                                                                    v-on="on"
                                                                    :error-messages="errors"
                                                                ></v-text-field>
                                                                </template>
                                                                <v-date-picker
                                                                v-model="getLeave.from"
                                                                no-title
                                                                scrollable
                                                                >
                                                                <v-spacer></v-spacer>
                                                                <v-btn
                                                                    text
                                                                    color="primary"
                                                                    @click="menu = false"
                                                                >
                                                                    Cancel
                                                                </v-btn>
                                                                <v-btn
                                                                    text
                                                                    color="primary"
                                                                    @click="$refs.menu.save(date)"
                                                                    @click.prevent="menu = false"
                                                                >
                                                                    OK
                                                                </v-btn>
                                                                </v-date-picker>
                                                            </v-menu>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" name="End Date" rules="required">
                                                            <v-menu
                                                                ref="menu2"
                                                                v-model="menu2"
                                                                :close-on-content-click="false"
                                                                :return-value.sync="date"
                                                                transition="scale-transition"
                                                                offset-y
                                                                min-width="auto"
                                                            >
                                                                <template v-slot:activator="{ on, attrs }">
                                                                <v-text-field
                                                                    v-model="getLeave.to"
                                                                    label="End date"
                                                                    prepend-icon="mdi-calendar"
                                                                    readonly
                                                                    v-bind="attrs"
                                                                    v-on="on"
                                                                    :error-messages="errors"
                                                                ></v-text-field>
                                                                </template>
                                                                <v-date-picker
                                                                v-model="getLeave.to"
                                                                no-title
                                                                scrollable
                                                                >
                                                                <v-spacer></v-spacer>
                                                                <v-btn
                                                                    text
                                                                    color="primary"
                                                                    @click="menu2 = false"
                                                                >
                                                                    Cancel
                                                                </v-btn>
                                                                <v-btn
                                                                    text
                                                                    color="primary"
                                                                    @click="$refs.menu2.save(date)"
                                                                >
                                                                    OK
                                                                </v-btn>
                                                                </v-date-picker>
                                                            </v-menu>
                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" name="Status" rules="required">
                                                            <v-select
                                                            :items="edit_status"
                                                            label="Status"
                                                            :error-messages="errors"
                                                            v-model="getLeave.status"
                                                            ></v-select>

                                                        </validation-provider>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" name="Status" rules="required">
                                                            <v-select
                                                            :items="edit_day"
                                                            label="Day"
                                                            :error-messages="errors"
                                                            v-model="getLeave.day"
                                                            ></v-select>

                                                        </validation-provider>
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

            <!-- DELETE LEAVE DIALOG -->
            <v-dialog v-model="dialogDelete" max-width="500px">
                <v-card flat>
                    <v-container>
                        <v-card outlined elevation="2">
                            <v-container>
                                <v-card-title class="headline justify-center">Are you sure to delete this request?</v-card-title>
                                <v-card-text class="text-uppercase">
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
                status: ['Pending', 'Denied', 'Approved'],
                edit_status: ['Pending', 'Denied', 'Approved'],
                day:['am','pm','whole-day'],
                edit_day:['am','pm','whole-day'],
                headers:
                [
                    { text: 'ID',           value: 'id', align: 'start' },
                    { text: 'FIRST NAME',   value: 'employee.first_name' },
                    { text: 'LAST NAME',    value: 'employee.last_name' },
                    { text: 'REASON',       value: 'description' },
                    { text: 'FROM',         value: 'from' },
                    { text: 'TO',           value: 'to' },
                    { text: 'DAYS',         value: 'no_of_days' },
                    { text: 'LEAVE CREDITS',value: 'employee.leave_credits' },
                    { text: 'STATUS',       value: 'status' },
                    { text: 'ACTIONS',      value: 'actions', sortable: false },
                ],
                leaves: [],
                leave: {},
                getLeave: {},

                // DIALOGS
                dialogAdd: false,
                dialogEdit: false,
                dialogDelete: false,

                search: '',
                 date: new Date().toISOString().substr(0, 10),
                 menu: false,
                 menu2: false,
                 employees:[]
            }
        },

        mounted(){
            this.getLeaves();
            this.getEmployees();
        },

        methods: {
            close() {
                this.dialogAdd = false
                this.dialogEdit = false
                this.dialogDelete = false
            },

            getLeaves() {
				axios.get('/api/admin_get-leaves').then(({ data }) => {
          			this.leaves = data;
        		})
			},
            getEmployees() {
				axios.get('/api/get-employees').then(({ data }) => {
          			this.employees = data;
        		})
			},

            reset() {
				this.$refs.ref_form.reset();
			},

            addLeave() {
                this.$refs.addPositionObserver.validate();
				axios.post('/api/admin_request-leave', this.leave)
                    .then((res) => {
                        console.log(res);
                          if(res.data.message == 'empty')
                        {
                            alert('Leave credit reached the limit');
                        }
                        else
                        {
                            alert('leave credits added');
                        }
					})
                this.getLeaves();
                this.close();
                this.reset();
			},

            editLeave(item) {
                this.getLeave = Object.assign({}, item)
                this.dialogEdit = true
            },

			updateLeave() {
                this.$refs.updatePositionObserver.validate();
				axios.put('/api/update-leave',this.getLeave)
                .then((res) => {
                        console.log(res);
                          if(res.data.message == 'empty')
                        {
                            alert('Leave credit reached the limit');
                        }
                        else
                        {
                            alert('leave credits added');
                        }
					})
                this.getLeaves();
                this.close();
			},

            deleteLeave(item) {
                this.getLeave = Object.assign({}, item)
                this.dialogDelete = true
            },

            deleteConfirm() {
				axios.delete(`/api/delete-leave/${this.getLeave.id}`)
                    .then(() => {
                        alert('leave deleted successfully');
                    })
                this.getLeaves();
                this.close();
			},
            cash_advance()
            {
                this.$router.push({ name: "Cash_advance"});
            }
        }
    }
</script>
