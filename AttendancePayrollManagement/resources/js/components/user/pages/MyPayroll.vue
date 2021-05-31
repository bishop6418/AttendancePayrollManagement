<template>
    <div >
        <v-card outlined elevation="2">
            <v-card-title>
                MY PAYROLL
                <!-- {{ moment(payroll.start_date).format("MMM D, YYYY") }} -
                {{ moment(payroll.end_date).format("MMM D, YYYY") }} -->
                <!-- <v-btn
                    style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                    @click="dialogAdd = true"
                    title="ADD PAYROLL"
                >
                    <v-icon>mdi-plus-thick</v-icon>
                </v-btn> -->
                <!-- <v-btn
                    style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                    @click="dialogDeletePayroll = true"
                    title="DELETE PAYROLL"
                >
                    <v-icon> mdi-delete </v-icon>
                </v-btn> -->
                        
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
                    :items="my_payroll"
                    :search="search"
                    dark
                >
                    <template v-slot:item.actions="{ item }">
                        <v-btn
                            small
                            :items = payslips
                            title="SHOW"
                            color="primary"
                            @click.prevent="showPayslip(item)"
                        >
                            Show 
                        </v-btn>
                        <!-- <v-btn
                            small
                            :items = payslips
                            title="DELETE"
                            color="error"
                            @click.prevent="deletePayslip(item)"
                        >
                            Del
                        </v-btn> -->
                    </template>
                </v-data-table>
            </v-container>

            <!-- <validation-observer v-slot="{ handleSubmit }" ref="addPayrollObserver">
                <v-dialog v-model="dialogAdd" max-width="500px">
                    <v-card flat>
                        <v-container>
                            <v-card outlined elevation="2">
                                <v-container>
                                    <v-form @submit.prevent="handleSubmit(createPayroll)" ref="ref_form">
                                        <v-card-title class="headline">Create Payroll</v-card-title>
                                        <v-divider></v-divider>
                                        <v-card-text>
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12" md="6">
                                                        <v-dialog
                                                            ref="dialog"
                                                            v-model="dialogStartDate"
                                                            persistent
                                                            width="290px"
                                                        >
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <validation-provider v-slot="{ errors }" name="Start Date" rules="required">
                                                                    <v-text-field
                                                                        v-model="payroll.start_date"
                                                                        label="Start Date"
                                                                        readonly
                                                                        v-bind="attrs"
                                                                        v-on="on"
                                                                        :error-messages="errors"
                                                                    >
                                                                    </v-text-field>
                                                                </validation-provider>
                                                            </template>
                                                            <v-date-picker
                                                                v-model="payroll.start_date"
                                                                scrollable
                                                                dark
                                                            >
                                                                <v-spacer></v-spacer>
                                                                <v-btn
                                                                    text
                                                                    @click="dialogStartDate=false"
                                                                >
                                                                    OK
                                                                </v-btn>
                                                                <v-btn
                                                                    text
                                                                    @click="dialogStartDate=false"
                                                                >
                                                                    Cancel
                                                                </v-btn>
                                                            </v-date-picker>
                                                        </v-dialog>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <v-dialog
                                                            ref="dialog"
                                                            v-model="dialogEndDate"
                                                            persistent
                                                            width="290px"
                                                        >
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <validation-provider v-slot="{ errors }" name="End Date" rules="required">
                                                                    <v-text-field
                                                                        v-model="payroll.end_date"
                                                                        label="End Date"
                                                                        readonly
                                                                        v-bind="attrs"
                                                                        v-on="on"
                                                                        :error-messages="errors"
                                                                    >
                                                                    </v-text-field>
                                                                </validation-provider>
                                                            </template>
                                                            <v-date-picker
                                                                v-model="payroll.end_date"
                                                                scrollable
                                                                dark
                                                            >
                                                                <v-spacer></v-spacer>
                                                                <v-btn
                                                                    text
                                                                    @click="dialogEndDate=false"
                                                                >
                                                                    OK
                                                                </v-btn>
                                                                <v-btn
                                                                    text
                                                                    @click="dialogEndDate=false"
                                                                >
                                                                    Cancel
                                                                </v-btn>
                                                            </v-date-picker>
                                                        </v-dialog>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-card-text>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn small type="submit"> Generate </v-btn>
                                            <v-btn small @click="close">Cancel</v-btn>
                                        </v-card-actions>
                                    </v-form>
                                </v-container>
                            </v-card>
                        </v-container>
                    </v-card>
                </v-dialog>
            </validation-observer> -->

            <!-- DELETE PAYROLL DIALOG -->
            <!-- <v-dialog v-model="dialogDeletePayroll" max-width="500px">
                <v-card flat>
                    <v-container>
                        <v-card outlined elevation="2">
                            <v-container>
                                <v-card-title class="headline justify-center">Are you sure to delete this payroll?</v-card-title>
                                <v-card-text class="text-uppercase">
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" md="6">
                                                <label> Start Date: </label>
                                                <p class="font-weight-bold"> {{ payroll.start_date }} </p>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <label> End Date: </label>
                                                <p class="font-weight-bold"> {{ payroll.end_date }} </p>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn small @click="deletePayrollConfirm">Delete</v-btn>
                                    <v-btn small @click="close">Cancel</v-btn>
                                </v-card-actions>
                            </v-container>
                        </v-card>
                    </v-container>
                </v-card>
            </v-dialog> -->

            <!-- DELETE PAYSLIP DIALOG
            <v-dialog v-model="dialogDeletePayslip" max-width="500px">
                <v-card flat>
                    <v-container>
                        <v-card outlined elevation="2">
                            <v-container>
                                <v-card-title class="headline justify-center">Are you sure to delete this payslip?</v-card-title>
                                <v-card-text class="text-uppercase">
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" md="2">
                                                <label> ID: </label>
                                                <p class="font-weight-bold"> {{ getPayslip.id }} </p>
                                            </v-col>
                                            <v-col cols="12" md="5">
                                                <label> First Name: </label>
                                                <p class="font-weight-bold"> {{ getPayslip.employee.first_name }} </p>
                                            </v-col>
                                            <v-col cols="12" md="5">
                                                <label> Last Name: </label>
                                                <p class="font-weight-bold"> {{ getPayslip.employee.last_name }} </p>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn small @click="deletePayslipConfirm">Delete</v-btn>
                                    <v-btn small @click="close">Cancel</v-btn>
                                </v-card-actions>
                            </v-container>
                        </v-card>
                    </v-container>
                </v-card>
            </v-dialog> -->
        </v-card>
    </div>
</template>

<script>
    var moment = require('moment');
    import axios from 'axios'
    // import { ValidationObserver, ValidationProvider, extend, setInteractionMode } from 'vee-validate';
  	// import { required, alpha_spaces, numeric } from 'vee-validate/dist/rules'
	//     setInteractionMode('eager')

	// 	extend('required', {
	// 		...required,
	// 		message: '{_field_} can not be empty'
	// 	})

	// 	extend('alpha_spaces', {
	// 		...alpha_spaces,
	// 		message: '{_field_} may only contain alphabetic characters as well as spaces'
	// 	})

	// 	extend('numeric', {
	// 		...numeric,
	// 		message: '{_field_} may only contain numeric characters',
	// 	})
        
    export default {
        // components: { ValidationObserver, ValidationProvider },
        data() {
            return {
                moment:moment,
                headers:
                [
                    { text: 'PAYSLIP ID',       value: 'id', align: 'start' },
                    { text: 'FROM',       value: 'payroll.start_date' },
                    { text: 'TO',        value: 'payroll.end_date' },
                    { text: 'GROSS SALARY',     value: 'gross_salary' },
                    { text: 'TOTAL DEDUCTION',  value: 'total_deduction' },
                    { text: 'NET SALARY',       value: 'net_salary' },
                    { text: 'ACTIONS',          value: 'actions', sortable: false },
                    // <!-- {{ moment(payroll.start_date).format("MMM D, YYYY") }} -
                // {{ moment(payroll.end_date).format("MMM D, YYYY") }} -->
                ],
                payslips:
                [
                    {
                        payroll: '',
                        employee: '',
                    }
                ],
                payroll: {},
                getPayslip: {
                    employee: ''
                },

                // // DIALOGS
                // dialogAdd: false,
                // dialogDeletePayroll: false,
                // dialogDeletePayslip: false,
                // dialogStartDate: false,
                // dialogEndDate: false,

                search: '',
                my_payroll:
                [
                    // {
                    //     start_date: '',
                    //     end_date:   ''
                    // }
                ],
                my_payroll2: ''
            }
        },
        mounted(){
            // this.payrollCurrentMonth();
            this.get_my_payroll();
        },
        methods: {
            get_my_payroll()
            {
                 axios.get('/api/my_payroll').then(({ data }) => {
                    this.my_payroll = data; 
                    // this.my_payroll.start_date = data.payroll.start_date;

                })
            },

            // close() {
            //     this.dialogAdd = false;
            //     this.dialogDeletePayroll = false;
            //     this.dialogDeletePayslip = false;
            // },
            getPayslips() {
                axios.post('/api/get-payslips', this.payroll)
                .then(({ data }) => {
                    this.payslips = data;
                    this.payroll.start_date = this.payslips[0].payroll.start_date;
                    this.payroll.end_date = this.payslips[0].payroll.end_date;
                })
            },
            payrollCurrentMonth()
            {
                axios.get('/api/current_month_date').then(({ data }) => {
                    this.payroll = data;
                    axios.post('/api/create-payroll', data)
                    this.getPayslips();
                })
            },
            // createPayroll() {
            //     this.$refs.addPayrollObserver.validate();
			// 	axios.post('/api/create-payroll', this.payroll)
            //     this.getPayslips();
            //     this.close();
			// },
            // deletePayrollConfirm() {
            //     axios.post('/api/delete-payroll', this.payslips[0])
            //         .then(() => {
            //             alert('deleted successfully');
            //         })
            //     this.getPayslips();
            //     this.close();
            // },
            showPayslip(item) {
                this.$router.push({ name: "ShowPayslip", params: { id: item.id } });
            },
            // deletePayslip(item) {
            //     this.getPayslip = Object.assign({}, item)
            //     this.dialogDeletePayslip = true
            // },
            // deletePayslipConfirm() {
            //     // axios.delete('/api/delete-payslip/' + this.getPayslip.id)
            //     axios.post('/api/delete-payslip', this.getPayslip)
            //         .then(() => {
            //             alert('deleted successfully');
            //         })
            //     this.close();
			// 	this.getPayslips();
            // },
            // thousandSeprator(amount) {
            //     if (amount !== '' || amount !== undefined || amount !== 0 || amount !== '0' || amount !== null) {
            //         return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            //     } else {
            //         return amount;
            //     }
            // },
            // toggleTheme() {
            //     this.$vuetify.theme.dark = !this.$vuetify.theme.dark;      
            // }
        }
    }
</script>