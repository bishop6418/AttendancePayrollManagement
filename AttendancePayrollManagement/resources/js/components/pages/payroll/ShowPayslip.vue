<template>
    <div id="showPayslip">
        <!-- <v-container max-width="500px"> -->
            <vue-html2pdf
                :show-layout="false"
                :float-layout="true"
                :enable-download="true"
                :preview-modal="false"
                :paginate-elements-by-height="1400"
                :filename="this.pdffilename"
                
                :pdf-quality="2"
                :manual-pagination="false"
                pdf-format="letter"
                pdf-orientation="portrait"
                
                @hasStartedGeneration="hasStartedGeneration()"
                @hasGenerated="hasGenerated($event)"
                ref="html2Pdf"
            >
                <section slot="pdf-content">
                    <v-card outlined elevation="2" max-width="1000px">
                        <v-card-title>
                            <v-row align="center">
                                <v-col cols="12" md="4" class="text-center">
                                    <p> PAYROLL </p>
                                </v-col>
                                <v-col cols="12" md="4" class="text-center">
                                    <p> SALARY SLIP </p>
                                </v-col>
                                <v-col cols="12" md="4" class="text-center">
                                    <p> CONFIDENTIAL </p>
                                </v-col>
                                <v-col cols="12" md="12" class="text-center">
                                    <p>
                                        {{ moment(employeePayslip.payroll.start_date).format("MMMM D, YYYY") }} - 
                                        {{ moment(employeePayslip.payroll.end_date).format("MMMM D, YYYY") }}
                                    </p>
                                </v-col>
                            </v-row>
                        </v-card-title>

                        <v-card-text>
                            <v-row dense>
                                <v-col cols="12" md="3">
                                    <p class="font-weight-bold"> Employee ID: </p>
                                    <p class="font-weight-bold"> Employee Name: </p>
                                    <p class="font-weight-bold"> Employee Designation: </p>
                                    <p class="font-weight-bold"> Remaining Leave: </p>
                                    <p class="font-weight-bold"> Days Leave: </p>
                                </v-col>
                                <v-col cols="12" md="3">
                                    <p> {{ employeePayslip.employee.id }} </p>
                                    <p> {{ employeePayslip.employee.first_name }} {{ employeePayslip.employee.last_name }} </p>
                                    <p> {{ employeePayslip.employee.position_id }} </p>
                                    <p> {{ employeePayslip.employee.leave_credits }} </p>
                                    <p> {{ employeePayslip.days_leave }} </p>
                                </v-col>
                                <v-col cols="12" md="3">
                                    <p class="font-weight-bold"> Total Weekdays: </p>
                                    <p class="font-weight-bold"> Days Worked: </p>
                                    <p class="font-weight-bold"> Days Absent: </p>
                                    <p class="font-weight-bold"> Late Counts: </p>
                                    <p class="font-weight-bold"> Saturday Worked: </p>
                                </v-col>
                                <v-col cols="12" md="3">
                                    <p> {{ employeePayslip.total_weekdays }} </p>
                                    <p> {{ employeePayslip.days_worked }} </p>
                                    <p> {{ employeePayslip.days_absent }} </p>
                                    <p> {{ employeePayslip.counts_late }} </p>
                                    <p> {{ employeePayslip.days_saturday }} </p>
                                </v-col>
                            </v-row>
                            <v-row align="center" dense>
                                <v-col cols="12" md="6" class="text-center">
                                    <p class="font-weight-black"> DESCRIPTION </p>
                                </v-col>
                                <v-col cols="12" md="3" class="text-center">
                                    <p class="font-weight-black"> EARNINGS </p>
                                </v-col>
                                <v-col cols="12" md="3" class="text-center">
                                    <p class="font-weight-black"> DEDUCTIONS </p>
                                </v-col>
                            </v-row>
                            <v-row align="center" dense>
                                <v-col cols="12" md="6">
                                    <p> Basic Salary: </p>
                                    <p> Additional: </p>
                                    <p> Saturday Pay: </p>
                                    <p v-for="deduction in employeeDetails.deductions"> {{ deduction.deduction_name }} </p>
                                </v-col>
                                <v-col cols="12" md="3" class="text-right">
                                    <p> {{ employeePayslip.basic_salary }} </p>
                                    <p> {{ employeePayslip.additional }} </p>
                                    <p> {{ employeePayslip.saturday_pay }} </p>
                                    <p v-for="deduction in employeeDetails.deductions"> - </p>
                                </v-col>
                                <v-col cols="12" md="3" class="text-right">
                                    <p> - </p>
                                    <p> - </p>
                                    <p> - </p>
                                    <p v-for="deduction in employeeDetails.deductions"> {{ deduction.pivot.amount }} </p>
                                </v-col>
                            </v-row>
                            <v-row align="center" dense>
                                <v-col cols="12" md="6">
                                    <p class="total"> TOTAL </p>
                                </v-col>
                                <v-col cols="12" md="3" class="text-right">
                                    <p class="total"> {{ employeePayslip.net_salary }} </p>
                                </v-col>
                                <v-col cols="12" md="3" class="text-right">
                                    <p class="total"> {{ employeePayslip.total_deduction }} </p>
                                </v-col>
                            </v-row>
                            <v-row align="center" dense>
                                <v-col cols="12" md="6" class="text-center">
                                    <p class="font-weight-black"> NET PAY </p>
                                </v-col>
                                <v-col cols="12" md="6" class="text-right">
                                    <p class="font-weight-black"> {{ employeePayslip.gross_salary }} </p>
                                </v-col>
                                <v-col cols="12" md="12" class="text-center">
                                    <p> {{ words }} </p>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </section>
            </vue-html2pdf>

            <v-card outlined elevation="2" max-width="1000px" ref="content">
                <v-btn
                    style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                    @click.prevent="goBack"
                    title="back"
                    top
                    left
                    absolute
                >
                    <v-icon>mdi-arrow-left-bold</v-icon>
                </v-btn>

                <v-card-title>
                    <v-row align="center">
                        <v-col cols="12" md="4" class="text-center">
                            <p> PAYROLL </p>
                        </v-col>
                        <v-col cols="12" md="4" class="text-center">
                            <p> SALARY SLIP </p>
                            <p>
                                {{ moment(employeePayslip.payroll.start_date).format("MMMM D, YYYY") }} - 
                                {{ moment(employeePayslip.payroll.end_date).format("MMMM D, YYYY") }}
                            </p>
                        </v-col>
                        <v-col cols="12" md="4" class="text-center">
                            <p> CONFIDENTIAL </p>
                        </v-col>
                    </v-row>
                </v-card-title>

                <v-card-text>
                    <v-row dense>
                        <v-col cols="12" md="3">
                            <p class="font-weight-bold"> Employee ID: </p>
                            <p class="font-weight-bold"> Employee Name: </p>
                            <p class="font-weight-bold"> Employee Designation: </p>
                            <p class="font-weight-bold"> Remaining Leave: </p>
                            <p class="font-weight-bold"> Days Leave: </p>
                        </v-col>
                        <v-col cols="12" md="3">
                            <p> {{ employeePayslip.employee.id }} </p>
                            <p> {{ employeePayslip.employee.first_name }} {{ employeePayslip.employee.last_name }} </p>
                            <p> {{ employeePayslip.employee.position_id }} </p>
                            <p> {{ employeePayslip.employee.leave_credits }} </p>
                            <p> {{ employeePayslip.days_leave }} </p>
                        </v-col>
                        <v-col cols="12" md="3">
                            <p class="font-weight-bold"> Total Weekdays: </p>
                            <p class="font-weight-bold"> Days Worked: </p>
                            <p class="font-weight-bold"> Days Absent: </p>
                            <p class="font-weight-bold"> Late Counts: </p>
                            <p class="font-weight-bold"> Saturday Worked: </p>
                        </v-col>
                        <v-col cols="12" md="3">
                            <p> {{ employeePayslip.total_weekdays }} </p>
                            <p> {{ employeePayslip.days_worked }} </p>
                            <p> {{ employeePayslip.days_absent }} </p>
                            <p> {{ employeePayslip.counts_late }} </p>
                            <p> {{ employeePayslip.days_saturday }} </p>
                        </v-col>
                    </v-row>
                    <v-row align="center" dense>
                        <v-col cols="12" md="6" class="text-center">
                            <p class="font-weight-black"> DESCRIPTION </p>
                        </v-col>
                        <v-col cols="12" md="3" class="text-center">
                            <p class="font-weight-black"> EARNINGS </p>
                        </v-col>
                        <v-col cols="12" md="3" class="text-center">
                            <p class="font-weight-black"> DEDUCTIONS </p>
                        </v-col>
                    </v-row>
                    <v-row align="center" dense>
                        
                        <v-col cols="12" md="6">
                            <p> Basic Salary: </p>
                            <p> Additional: </p>
                            <p> Saturday Pay: </p>
                            <p v-for="deduction in employeeDetails.deductions"> {{ deduction.deduction_name }} </p>
                        </v-col>
                        <v-col cols="12" md="3" class="text-right">
                            <p> {{ employeePayslip.basic_salary }} </p>
                            <p> {{ employeePayslip.additional }} </p>
                            <p> {{ employeePayslip.saturday_pay }} </p>
                            <p v-for="deduction in employeeDetails.deductions"> - </p>
                            
                        </v-col>
                        <v-col cols="12" md="3" class="text-right">
                            <p> - </p>
                            <p> - </p>
                            <p> - </p>
                            <p v-for="deduction in employeeDetails.deductions"> {{ deduction.pivot.amount }} </p>
                            
                        </v-col>
                    </v-row>
                    <v-row align="center" dense>
                        <v-col cols="12" md="6">
                            <p class="total"> TOTAL </p>
                        </v-col>
                        <v-col cols="12" md="3" class="text-right">
                            <p class="total"> {{ employeePayslip.gross_salary }} </p>
                        </v-col>
                        <v-col cols="12" md="3" class="text-right">
                            <p class="total"> {{ employeePayslip.total_deduction }} </p>
                        </v-col>
                    </v-row>
                    <v-row align="center" dense>
                        <v-col cols="12" md="6" class="text-center">
                            <p class="font-weight-black"> NET PAY </p>
                        </v-col>
                        <v-col cols="12" md="6" class="text-right">
                            <p class="font-weight-black"> {{ employeePayslip.net_salary }} </p>
                        </v-col>
                        <v-col cols="12" md="12" class="text-center">
                            <p> {{ words }} </p>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-btn
                    small
                    @click="generateReport"
                    top
                    right
                    absolute
                    title="download pdf"
                >
                    <v-icon>mdi-file-pdf</v-icon>
                </v-btn>
            </v-card>
        <!-- </v-container> -->
    </div>
</template>

<script>
    import { ToWords } from 'to-words'
    export default {
        data() {
            return {
                employeePayslip: {
                    payroll: '',
                    employee: ''
                },
                employeeDetails: {
                    position: '',
                    deductions: [
                        {
                            deduction_name: '',
                            pivot: ''
                        }
                    ],
                },
                payslip: {},
                search: '',
                words: '',
                pdffilename: ''
            }
        },
        created() {
            this.payslip.id = this.$route.params.payslip_id;
        },
        mounted(){
            this.getEmployeePayslip();
        },
        methods: {
            goBack() {
                this.$router.push({ name: "Payslip", params: { payroll_id: this.employeePayslip.payroll_id } });
                // this.$router.push({ name: "ShowPayslip", params: { payslip_id: item.id } });
            },
            getEmployeePayslip() {
                axios.get('/api/get-employee-payslip/' + this.payslip.id)
                    .then(({ data }) => {
                        this.employeePayslip = data;
                        const toWords = new ToWords({
                            localeCode: 'en-PH',
                            converterOptions: {
                                currency: true,
                                ignoreDecimal: false,
                                ignoreZeroCurrency: false,
                            }
                        });
                        this.words = toWords.convert(this.employeePayslip.net_salary);
                        this.pdffilename = this.employeePayslip.employee.last_name+"'s Payslip("+this.employeePayslip.payroll.start_date+","+this.employeePayslip.payroll.start_date+")";
                        
                        axios.get('/api/get-employee/' + this.employeePayslip.employee_id)
                            .then(({ data }) => {
                                this.employeeDetails = data;
                            })
                    })
            },
            generateReport () {
                const pdf = this.$refs.html2Pdf.generatePdf();
            }
        }
        // components: {
        //     VueHtml2pdf
        // }
    }
</script>