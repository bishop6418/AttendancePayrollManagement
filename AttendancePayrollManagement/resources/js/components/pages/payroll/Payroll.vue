<template>
    <div >
        <v-card outlined elevation="2">
            <v-card-title>
                Payrolls
            
                <v-btn
                    style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                    @click="dialogAdd = true"
                    title="add"
                >
                    <v-icon> mdi-plus-thick </v-icon>
                </v-btn>
                <v-btn
                    style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                    title="download pdf"
                    @click="generateReport"
                >
                    <v-icon> mdi-file-pdf </v-icon>
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
                    :items="payrolls"
                    :search="search"
                    dark
                >
                    <template v-slot:item.start_date="{ item }">
                        {{ moment(item.start_date).format("MMMM D, YYYY") }}
                    </template>

                    <template v-slot:item.end_date="{ item }">
                        {{ moment(item.end_date).format("MMMM D, YYYY") }}
                    </template>
                    
                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            small
                            @click.prevent="generate(item)"
                            title="generate payslip"
                        >
                            mdi-cogs
                        </v-icon>
                        <v-icon
                            small
                            @click.prevent="deletePayroll(item)"
                            title="delete"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                </v-data-table>
            </v-container>

            <!-- ADD PAYROLL DIALOG -->
            <validation-observer v-slot="{ handleSubmit }" ref="addPayrollObserver">
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
                                                                :min="disableStartDate"
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
                                                                :min="payroll.start_date ? payroll.start_date : disableStartDate"
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
                                            <v-btn small type="submit"> Create </v-btn>
                                            <v-btn small @click="close">Cancel</v-btn>
                                        </v-card-actions>
                                    </v-form>
                                </v-container>
                            </v-card>
                        </v-container>
                    </v-card>
                </v-dialog>
            </validation-observer>

            <!-- DELETE PAYROLL DIALOG -->
            <v-dialog v-model="dialogDelete" max-width="500px">
                <v-card flat>
                    <v-container>
                        <v-card outlined elevation="2">
                            <v-container>
                                <v-card-title class="headline justify-center">Are you sure to delete this payroll?</v-card-title>
                                <v-card-text class="text-uppercase">
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" md="2">
                                                <label> ID: </label>
                                                <p class="font-weight-bold"> {{ getPayroll.id }} </p>
                                            </v-col>
                                            <v-col cols="12" md="5">
                                                <label> Start Date: </label>
                                                <p class="font-weight-bold"> {{ getPayroll.start_date }} </p>
                                            </v-col>
                                            <v-col cols="12" md="5">
                                                <label> End Date: </label>
                                                <p class="font-weight-bold"> {{ getPayroll.end_date }} </p>
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
        <vue-html2pdf
            :show-layout="false"
            :float-layout="true"
            :enable-download="true"
            :preview-modal="false"
            :paginate-elements-by-height="1400"
            filename="Payroll"
            
            :pdf-quality="2"
            :manual-pagination="false"
            pdf-format="letter"
            pdf-orientation="portrait"
            
            
            @hasStartedGeneration="hasStartedGeneration()"
            @hasGenerated="hasGenerated($event)"
            ref="html2Pdf"
        >
            <section slot="pdf-content">
                <v-data-table
                    :headers="headers2"
                    :items="payrolls"
                    :search="search"
                    dark
                >
                    <template v-slot:item.start_date="{ item }">
                        {{ moment(item.start_date).format("MMMM D, YYYY") }}
                    </template>

                    <template v-slot:item.end_date="{ item }">
                        {{ moment(item.end_date).format("MMMM D, YYYY") }}
                    </template>
                </v-data-table>
            </section>
        </vue-html2pdf>
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
                    { text: 'ID',               value: 'id', align: 'start' },
                    { text: 'START DATE',       value: 'start_date' },
                    { text: 'END DATE',         value: 'end_date' },
                    { text: 'TOTAL GROSS',      value: 'total_gross' },
                    { text: 'TOTAL EMPLOYEE',   value: 'total_employee' },
                    { text: 'ACTIONS',          value: 'actions', sortable: false },
                ],
                headers2:
                [
                    { text: 'ID',               value: 'id', align: 'start' },
                    { text: 'START DATE',       value: 'start_date' },
                    { text: 'END DATE',         value: 'end_date' },
                    { text: 'TOTAL GROSS',      value: 'total_gross' },
                    { text: 'TOTAL EMPLOYEE',   value: 'total_employee' }
                ],
                payrolls: [],
                payroll: {},
                getPayroll: {},
                disableStartDate: '',

                // DIALOGS
                dialogAdd: false,
                dialogDelete: false,
                dialogStartDate: false,
                dialogEndDate: false,

                search: '',
            }
        },
        // computed: {
        //     defaultEndDate() {
        //         const now = new Date();
        //         now.setDate(1);
        //         this.disableStartDate = now.toISOString().slice(0, 10);
        //     }
        // },
        mounted() {
            this.getPayrolls();
            this.defaultStartDate();
        },
        methods: {
            close() {
                this.dialogAdd = false
                this.dialogDelete = false
            },
            getPayrolls() {
				axios.get('/api/get-payrolls').then(({ data }) => {
          			this.payrolls = data;
        		})
			},
            reset() {
				this.$refs.ref_form.reset();
			},
            createPayroll() {
                this.$refs.addPayrollObserver.validate();
				axios.post('/api/create-payroll', this.payroll)
                    .then((res) => {
                        if(res.data.message == 'empty')
                        {
                            alert('no employee worked');
                        }
                        else
                        {
                            alert('payroll created successfully');
                        }
					})
                this.getPayrolls();
                this.close();
                this.reset();
			},
            deletePayroll(item) {
                this.getPayroll = Object.assign({}, item)
                this.dialogDelete = true
            },
            deleteConfirm() {
				// axios.post('/api/delete-payroll', this.payslips[0])
                axios.post('/api/delete-payroll', this.getPayroll)
                    .then(() => {
                        alert('payroll deleted successfully');
                    })
                this.getPayrolls();
                this.close();
			},
            generate(item) {
                this.$router.push({ name: "Payslip", params: { payroll_id: item.id } });
            },
            formatDate(value) {
                return moment(value).format("MMM D, YYYY")
            },
            generateReport () {
                this.$refs.html2Pdf.generatePdf()
            },
            defaultStartDate() {
                const now = new Date();
                now.setDate(1);
                this.disableStartDate = now.toISOString().slice(0, 10);
            }
        }
    }
</script>