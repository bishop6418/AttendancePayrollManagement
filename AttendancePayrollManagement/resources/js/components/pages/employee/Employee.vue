<template>
    <div >
        <v-card outlined elevation="2">
            <v-card-title>
                Employees

                <v-btn
                    style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                    @click.prevent="addEmployee"
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
                    :items="employees"
                    :search="search"
                    dark
                >
                    <template v-slot:item.date_hired="{ item }">
                        {{ moment(item.date_hired).format("MMMM D, YYYY") }}
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            small
                            @click.prevent="showEmployee(item)"
                            title="show"
                        >
                            mdi-eye
                        </v-icon>
                        <v-icon
                            small
                            @click.prevent="deleteEmployee(item)"
                            title="delete"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                </v-data-table>
            </v-container>

            <!-- DELETE POSITION DIALOG -->
            <v-dialog v-model="dialogDelete" max-width="500px">
                <v-card flat>
                    <v-container>
                        <v-card outlined elevation="2">
                            <v-container>
                                <v-card-title class="headline justify-center">Are you sure to delete this employee?</v-card-title>
                                <v-card-text class="text-uppercase">
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" md="6">
                                                <label> First Name: </label>
                                                <p class="font-weight-bold"> {{ getEmployee.first_name }} </p>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <label> Last Name: </label>
                                                <p class="font-weight-bold"> {{ getEmployee.last_name }} </p>
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
                    { text: 'ID',           value: 'id', align: 'start' },
                    { text: 'FIRST NAME',   value: 'first_name' },
					{ text: 'LAST NAME',    value: 'last_name' },
                    { text: 'EMAIL',		value: 'email' },
					{ text: 'DATE HIRED',	value: 'date_hired' },
                    { text: 'POSITION',     value: 'position.name' },
                    { text: 'SCHEDULE',     value: 'schedule.shift' },
                    { text: 'ACTIONS',      value: 'actions', sortable: false }
                ],
                employees: [],
                employee: {},
                getEmployee: {},

                // DIALOGS
                dialogAdd: false,
                dialogEdit: false,
                dialogDelete: false,

                search: '',
            }
        },

        mounted(){
            this.getEmployees();
        },

        methods: {
			addEmployee() {
                this.$router.push({ name: "Add-Employee" });
            },
            close() {
                this.dialogAdd = false
                this.dialogEdit = false
                this.dialogDelete = false
            },
            getEmployees() {
				axios.get('/api/get-employees').then(({ data }) => {
          			this.employees = data;
        		})
			},

			// SHOW EMPLOYEE
			showEmployee(item) {
				this.getEmployee = Object.assign({}, item)
				this.$router.push({ name: "Show-Employee", params: { employee_id: item.id } });
            },

			// DELETE EMPLOYEE
            deleteEmployee(item) {
                this.getEmployee = Object.assign({}, item)
                this.dialogDelete = true
            },
            deleteConfirm() {
				axios.post('/api/delete-employee', this.getEmployee)
                    .then(() => {
                        alert('employee deleted successfully');
                    })
                this.close();
				this.getEmployees();
			}
        }
    }
</script>
