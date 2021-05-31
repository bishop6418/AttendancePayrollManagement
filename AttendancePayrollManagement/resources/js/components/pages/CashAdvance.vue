<template>
    <div >
        <v-card outlined elevation="2">
            <v-card-title>
                CASH ADVANCE

                <v-btn
                    style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                    @click="dialogAdd = true"
                    title="add"
                >
                    <v-icon>mdi-plus-thick</v-icon>
                </v-btn>
                <v-btn
                    style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                    @click = "back"
                >
                    Back
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
                    :items="deductions"
                    :search="search"
                    dark
                >
                    <template v-slot:item.pivot.date_deducted="{ item }">
                        {{ moment(item.pivot.date_deducted).format("MMMM D, YYYY, h:mm:ss a") }}
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            small
                            @click.prevent="editCashAdvance(item)"
                            title="edit"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click.prevent="deleteCashAdvance(item)"
                            title="delete"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                </v-data-table>
            </v-container>

            <!-- ADD CASH ADVANCE DIALOG -->
            <validation-observer v-slot="{ handleSubmit }" ref="addCashAdvanceObserver">
                <v-dialog v-model="dialogAdd" max-width="500px">
                    <v-card flat>
                        <v-container>
                            <v-card outlined elevation="2">
                                <v-container>
                                    <v-form @submit.prevent="handleSubmit(addCashAdvance)" ref="ref_form">
                                        <v-card-title class="headline">CASH ADVANCE</v-card-title>
                                        <v-divider></v-divider>
                                        <v-card-text>
                                            <v-container>
                                                <v-row>
                                                     <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" name="Employee Name" rules="required">
                                                            <v-autocomplete
                                                                v-model="cashAdvance.employee"
                                                                :items="employees"
                                                                :error-messages="errors"
                                                                value="'id'"
                                                                dense
                                                                filled
                                                                label="Enter Employee Name"
                                                            >
                                                                <template slot="selection" slot-scope="data">
                                                                    {{ data.item.first_name }} {{ data.item.last_name }}
                                                                </template>
                                                                <template slot="item" slot-scope="data">
                                                                    {{ data.item.first_name }} {{ data.item.last_name }}
                                                                </template>
                                                            </v-autocomplete>
                                                        </validation-provider>
                                                            <!-- <v-select
                                                                v-if="employees"
                                                                v-model="cashAdvance.employee_id"
                                                                :error-messages="errors"
                                                                label="Select Employee"
                                                                :items="employees"
                                                                :item-text="'first_name'||'last_name'"
                                                                :item-value="'id'"
                                                            ></v-select> -->
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" name="Amount" rules="required">
                                                            <v-text-field
                                                                v-model="cashAdvance.amount"
                                                                label="Amount"
                                                                :error-messages="errors"
                                                            ></v-text-field>
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

            <!-- EDIT CASH ADVANCE DIALOG -->
            <validation-observer v-slot="{ handleSubmit }" ref="updateCashAdvanceObserver">
                <v-dialog v-model="dialogEdit" max-width="500px">
                    <v-card flat>
                        <v-container>
                            <v-card outlined elevation="2">
                                <v-container>
                                    <v-form @submit.prevent="handleSubmit(updateCashAdvance)" ref="ref_form">
                                        <v-card-title class="headline">Edit Cash Advance</v-card-title>
                                        <v-divider></v-divider>
                                        <v-card-text>
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12" md="6">
                                                        <label> Employee Name: </label>
                                                        <p class="font-weight-bold"> {{ getCashAdvance.first_name }} {{ getCashAdvance.last_name }} </p>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <validation-provider v-slot="{ errors }" name="Reason" rules="required|numeric">
                                                             <v-text-field
                                                                v-model="getCashAdvance.pivot.amount"
                                                                label="Amount"
                                                                :error-messages="errors"
                                                            ></v-text-field>
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

            <!-- DELETE CASH ADVANCE DIALOG -->
            <v-dialog v-model="dialogDelete" max-width="500px">
                <v-card flat>
                    <v-container>
                        <v-card outlined elevation="2">
                            <v-container>
                                <v-card-title class="headline justify-center">Are you sure to delete this?</v-card-title>
                                <v-card-text class="text-uppercase">
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" md="6">
                                                <label> Employee Name: </label>
                                                <p class="font-weight-bold"> {{ getCashAdvance.first_name }} {{ getCashAdvance.last_name }} </p>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <label> Amount: </label>
                                                <p class="font-weight-bold"> {{ getCashAdvance.pivot.amount }} </p>
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
  	import { required, numeric } from 'vee-validate/dist/rules'
	    setInteractionMode('eager')

		extend('required', {
			...required,
			message: '{_field_} can not be empty'
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
                    { text: 'AMOUNT',       value: 'pivot.amount' },
                    { text: 'DATE CREATED', value: 'pivot.date_deducted' },
                    { text: 'ACTIONS',      value: 'actions', sortable: false },
                ],
                deductions: [],
                employees:[],
                cashAdvance: {},
                getCashAdvance: {
                    pivot: {}
                },

                // DIALOGS
                dialogAdd: false,
                dialogEdit: false,
                dialogDelete: false,

                search: '',
            }
        },

        mounted(){
            this.getDeductions();
            this.getEmployees();
        },

        methods: {
            close() {
                this.dialogAdd = false
                this.dialogEdit = false
                this.dialogDelete = false
            },
            back()
            {
                this.$router.push({ name: "Request"});
            },
            getDeductions() {
                axios.get('/api/get-employee-deductions').then(({ data }) => {
          			this.deductions = data[0].employees;
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
            addCashAdvance() {
                this.$refs.addCashAdvanceObserver.validate();
				axios.post('/api/add-employee-deduction', this.cashAdvance)
                this.getDeductions();
                this.close();
                this.reset();
			},
            editCashAdvance(item) {
                this.getCashAdvance = Object.assign({}, item)
                this.dialogEdit = true
            },
			updateCashAdvance() {
                this.$refs.updateCashAdvanceObserver.validate();
				axios.post('/api/update-employee-deduction', this.getCashAdvance)
                    .then(() => {
                        alert('successfully updated');
					})
                this.getDeductions();
                this.close();
			},
            deleteCashAdvance(item) {
                this.getCashAdvance = Object.assign({}, item)
                this.dialogDelete = true
            },
            deleteConfirm() {
				axios.post('/api/delete-employee-deduction', this.getCashAdvance)
                    .then(() => {
                        alert('successfully deleted');
                    })
                this.getDeductions();
                this.close();
			}
        }
    }
</script>
