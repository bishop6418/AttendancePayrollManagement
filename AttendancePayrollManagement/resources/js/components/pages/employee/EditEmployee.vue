<template>
	<div>
		<validation-observer v-slot="{ handleSubmit }" ref="observer">
		    <v-card flat id="editEmp">
                <v-card-title>
                    Edit Employee
                    <v-spacer></v-spacer>
                    <v-btn
						small
                        @click.prevent="goto"
                    >
                        <v-icon left>mdi-arrow-left-bold</v-icon>BACK
                    </v-btn>
                </v-card-title>
				<v-form @submit.prevent="handleSubmit(updateEmployee)">
					<v-row>
						<v-col cols="12" md="6">

							<!-- PERSONAL DETAILS -->
								<v-container>
									<v-container>
										<h4> Personal Details </h4>
										<v-divider></v-divider>
										<v-row>
											<v-col
												cols="12"
												md="6"
											>
												<validation-provider v-slot="{ errors }" name="First Name" rules="required|alpha_spaces">
												<v-text-field
													v-model="employee.first_name"
													label="First Name"
													:error-messages="errors"
												>
													<v-icon
														slot="prepend"
													>
														mdi-account
													</v-icon>
												</v-text-field>
												</validation-provider>
											</v-col>
											<v-col
												cols="12"
												md="6"
											>
												<validation-provider v-slot="{ errors }" name="Last Name" rules="required|alpha_spaces">
												<v-text-field
													v-model="employee.last_name"
													label="Last Name"
													:error-messages="errors"
												>
													<v-icon
														slot="prepend"
													>
														mdi-account
													</v-icon>
												</v-text-field>
												</validation-provider>
											</v-col>
										</v-row>
										<v-row>
											<v-col
												cols="12"
												md="6"
											>
												<validation-provider v-slot="{ errors }" name="Contact Number" rules="required|digits:11">
												<v-text-field
													v-model="employee.contact_number"
													label="Contact Number"
													:error-messages="errors"
												>
													<v-icon
														slot="prepend"
													>
														mdi-phone
													</v-icon>
												</v-text-field>
												</validation-provider>
											</v-col>
											<v-col
												cols="12"
												md="6"
											>
												<v-dialog
													ref="dialog"
													v-model="dialogBirthDate"
													persistent
													width="290px"
												>
													<template v-slot:activator="{ on, attrs }">
														<validation-provider v-slot="{ errors }" name="Birth Date" rules="required">
														<v-text-field
															v-model="employee.birthdate"
															label="Birth Date"
															readonly
															v-bind="attrs"
															v-on="on"
															:error-messages="errors"
														>
															<v-icon
																slot="prepend"
															>
																mdi-calendar
															</v-icon>
														</v-text-field>
														</validation-provider>
													</template>
													<v-date-picker
														v-model="employee.birthdate"
														scrollable
														dark
													>
														<v-spacer></v-spacer>
														<v-btn
															text
															@click="close"
														>
															OK
														</v-btn>
														<v-btn
															text
															@click="close"
														>
															Cancel
														</v-btn>
													</v-date-picker>
												</v-dialog>
											</v-col>
										</v-row>
										<v-row>
											<v-col
												cols="12"
												md="12"
											>
												<validation-provider v-slot="{ errors }" name="Address" rules="required">
												<v-text-field
													v-model="employee.address"
													label="Address"
													:error-messages="errors"
												>
													<v-icon
														slot="prepend"
													>
														mdi-home
													</v-icon>
												</v-text-field>
												</validation-provider>
											</v-col>
										</v-row>
										<v-row>
											<v-col
												cols="12"
												md="6"
											>
												<validation-provider v-slot="{ errors }" name="Gender" rules="required">
												<v-select
													:items="gender"
													label="Gender"
													v-model="employee.gender"
													:error-messages="errors"
												>
													<v-icon
														slot="prepend"
													>
														mdi-gender-male-female-variant
													</v-icon>
												</v-select>
												</validation-provider>
											</v-col>
											<v-col
												cols="12"
												md="6"
											>
												<validation-provider v-slot="{ errors }" name="Marital Status" rules="required">
												<v-select
													:items="marital"
													label="Marital Status"
													v-model="employee.marital_status"
													:error-messages="errors"
												>
													<v-icon
														slot="prepend"
													>
														mdi-ring
													</v-icon>
												</v-select>
												</validation-provider>
											</v-col>
										</v-row>
									</v-container>
								</v-container>
							<br>

							<!-- FINANCIAL DETAILS -->
								<v-container>
									<v-container>
										<h4> Financial Details </h4>
										<v-divider></v-divider>
										<v-row>
											<v-col
												cols="12"
												md="6"
											>
												<validation-provider v-slot="{ errors }" name="Rate Type" rules="required">
												<v-select
													:items="rate_type"
													label="Rate Type"
													v-model="employee.rate.rate_type"
													:error-messages="errors"
												></v-select>
												</validation-provider>
											</v-col>
											<v-col
												cols="12"
												md="6"
											>
												<validation-provider v-slot="{ errors }" name="Basic Salary" rules="required|numeric">
												<v-text-field
													v-model="employee.rate.basic_salary"
													label="Basic Salary"
													:error-messages="errors"
												></v-text-field>
												</validation-provider>
											</v-col>
											<v-col
												cols="12"
												md="6"
											>
												<validation-provider v-slot="{ errors }" name="Additional Salary" rules="numeric">
												<v-text-field
													v-model="employee.rate.additional"
													label="Additional Salary"
													:error-messages="errors"
												></v-text-field>
												</validation-provider>
											</v-col>
											<v-col
												cols="12"
												md="6"
											>
												<v-card-text> Total Salary: {{ Number(employee.rate.basic_salary) + Number(employee.rate.additional) }} </v-card-text>
											</v-col>
										</v-row>
									</v-container>
								</v-container>
						</v-col>

						<v-col cols="12" md="6">
							<!-- COMPANY DETAILS -->
								<v-container>
									<v-container>
										<h4> Company Details </h4>
										<v-divider></v-divider>
										<v-row>
											<v-col
												cols="12"
												md="6"
											>
												<v-dialog
													ref="dialog"
													v-model="dialogDateHired"
													persistent
													width="290px"
												>
													<template v-slot:activator="{ on, attrs }">
														<validation-provider v-slot="{ errors }" name="Date Hired" rules="required">
														<v-text-field
															v-model="employee.date_hired"
															label="Date Hired"
															readonly
															v-bind="attrs"
															v-on="on"
															:error-messages="errors"
														>
															<v-icon
																slot="prepend"
															>
																mdi-calendar
															</v-icon>
														</v-text-field>
														</validation-provider>
													</template>
													<v-date-picker
														v-model="employee.date_hired"
														scrollable
														dark
													>
														<v-spacer></v-spacer>
														<v-btn
															text
															@click="close"
														>
															OK
														</v-btn>
														<v-btn
															text
															@click="close"
														>
															Cancel
														</v-btn>
													</v-date-picker>
												</v-dialog>
											</v-col>
											<v-col
												cols="12"
												md="6"
											>
												<validation-provider v-slot="{ errors }" name="Leave Credits" rules="required|numeric">
												<v-text-field
													v-model="employee.leave_credits"
													label="Leave Credits"
													:error-messages="errors"
												></v-text-field>
												</validation-provider>
											</v-col>
										</v-row>
										<v-row>
											<v-col
												cols="12"
												md="6"
											>
												<validation-provider v-slot="{ errors }" name="Schedule" rules="required">
												<v-select
													v-if="schedules"
													v-model="employee.schedule_id"
													:error-messages="errors"
													label="Select Schedule"
													:items="schedules"
													:item-text="'shift'"
													:item-value="'id'"
												>
													<v-icon
														slot="prepend"
													>
														mdi-clock-outline
													</v-icon>
												</v-select>
												</validation-provider>
											</v-col>
											<v-col
												cols="12"
												md="6"
											>
												<validation-provider v-slot="{ errors }" name="Position" rules="required">
												<v-select
													v-if="positions"
													v-model="employee.position_id"
													:error-messages="errors"
													label="Select Position"
													:items="positions"
													:item-text="'name'"
													:item-value="'id'"
													v-on:change="onSelectPosition"
												></v-select>
												</validation-provider>
											</v-col>
										</v-row>
										<v-row>
											<v-col
												cols="12"
												md="12"
											>
												<validation-provider v-slot="{ errors }" name="Email" rules="required|email">
												<v-text-field
													v-model="employee.email"
													label="Email"
													type="email"
													:error-messages="errors"
												>
													<v-icon
														slot="prepend"
													>
														mdi-email
													</v-icon>
												</v-text-field>
												</validation-provider>
											</v-col>
										</v-row>
									</v-container>
								</v-container>
							<br>

							<!-- BENEFITS DETAILS -->
								<v-container>
									<v-container>
										<h4> 
											Benefits Details
											<v-btn
												style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
												@click.prevent="addBenefit(employee.benefits[0])"
												title="add"
											>
												<v-icon>mdi-plus</v-icon>
											</v-btn>
										</h4>
										<v-divider></v-divider>
										<v-row align="center" v-for="benefit in employee.benefits" :key="benefit.id">
											<v-col
												cols="12"
												md="4"
											>
												<v-checkbox
													:value="benefit.id"
													:label="benefit.name"
													v-model="benefit.id"
												></v-checkbox>
											</v-col>
											<v-col
												cols="12"
												md="8"
											>
												<validation-provider v-slot="{ errors }" name="Reference Number" rules="alpha_num">
												<v-text-field
													v-model="benefit.pivot.ref_number"
													:disabled="!benefit.id"
													:label="benefit.name + ' -- Reference Number'"
													:error-messages="errors"
												>
													<v-btn
														slot="append"
														@click.prevent="deleteBenefit(benefit)"
														style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
														text
														title="delete"
													>
														<v-icon> mdi-close </v-icon>
													</v-btn>
												</v-text-field>
												</validation-provider>
											</v-col>
										</v-row>
									</v-container>
								</v-container>
						</v-col>
					</v-row>
					<v-btn absolute bottom right type="submit" > Update </v-btn>
					<!-- <v-card-actions>
						<v-spacer></v-spacer>
						<v-btn type="submit" > Update </v-btn>
					</v-card-actions> -->
				</v-form>
            </v-card>
		</validation-observer>

		<!-- ADD BENEFIT DIALOG -->
		<validation-observer v-slot="{ handleSubmit }" ref="addBenefitObserver">
			<v-dialog v-model="dialogAdd" max-width="500px">
				<v-card flat>
					<v-container>
						<v-card outlined elevation="2">
							<v-container>
								<v-form @submit.prevent="handleSubmit(addBenefitConfirm)" ref="ref_form">
									<v-card-title class="headline">Add Benefit</v-card-title>
									<v-divider></v-divider>
									<v-card-text>
										<v-container>
											<v-row align="center" v-for="(benefit, index) in benefits" :key="benefit.id">
												<v-col
													cols="12"
													md="4"
												>
													<v-checkbox
														:value="benefit.id"
														:key="benefit.id"
														:hide-details="index !== benefits.length - 1"
														:label="benefit.name"
														v-model="form.benefits[index].id"
													></v-checkbox>
												</v-col>
												<v-col
													cols="12"
													md="8"
												>
													<validation-provider v-slot="{ errors }" name="Reference Number" rules="alpha_num">
													<v-text-field
														v-model="form.benefits[index].ref_number"
														:key="benefit.id"
														:hide-details="index !== benefits.length - 1"
														:disabled="!form.benefits[index].id"
														:label="benefit.name + ' -- Reference Number'"
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
		
		<!-- DELETE BENEFIT DIALOG -->
		<v-dialog v-model="dialogDelete" max-width="500px">
			<v-card flat>
				<v-container>
					<v-card outlined elevation="2">
						<v-container>
							<v-card-title class="headline justify-center">Are you sure to delete this benefit?</v-card-title>
							<v-card-text class="text-uppercase">
								<v-container>
									<v-row>
										<v-col cols="12" md="6">
											<label> Benefit Name: </label>
											<p class="font-weight-bold"> {{ getBenefit.name }} </p>
										</v-col>
										<v-col cols="12" md="6">
											<label> Reference Number: </label>
											<p class="font-weight-bold"> {{ getBenefit.pivot.ref_number }} </p>
										</v-col>
									</v-row>
								</v-container>
							</v-card-text>
							<v-card-actions>
								<v-spacer></v-spacer>
								<v-btn small @click="deleteBenefitConfirm">Yes</v-btn>
								<v-btn small @click="close">Cancel</v-btn>
							</v-card-actions>
						</v-container>
					</v-card>
				</v-container>
			</v-card>
		</v-dialog>
	</div>
</template>

<script>
	import { ValidationObserver, ValidationProvider, extend, setInteractionMode } from 'vee-validate';
  	import { required, alpha_spaces, alpha_num, digits, email, numeric } from 'vee-validate/dist/rules'

	   setInteractionMode('eager')

		extend('required', {
			...required,
			message: '{_field_} can not be empty'
		})

		extend('alpha_spaces', {
			...alpha_spaces,
			message: '{_field_} may only contain alphabetic characters as well as spaces'
		})

		extend('alpha_num', {
			...alpha_num,
			message: '{_field_} may only contain alpha-numeric characters'
		})

		extend('email', {
			...email,
			message: 'Email must be valid',
		})

		extend('digits', {
			...digits,
			message: '{_field_} needs to be {length} digits. ({_value_})',
		})

		extend('numeric', {
			...numeric,
			message: '{_field_} may only contain numeric characters',
		})

	export default {
		components: { ValidationObserver, ValidationProvider },
		data() {
			return {
				// DIALOGS
				enabled: false,
				dialogBirthDate: false,
				dialogDateHired: false,
				dialogAdd: false,
				dialogDelete: false,

				benefits: null,
				positions: null,
				schedules: null,
				employee_id: {},
				employee: {
					rate: {},
					benefits: [
						{
							pivot: {
								ref_number: ''
							}
						}
					]
				},
				getBenefit: {
					pivot: {
						employee_id: ''
					}
				},
				form: {
					benefits: [
						{
							ref_number: ''
						}
					],
				},

				gender: ['Male', 'Female'],
				rate_type: ['Daily', 'Monthly'],
				marital: ['Single', 'Engaged', 'Married', 'Separated', 'Widowed', 'Divorced'],
				errors: []
			}
		},
		created() {
			this.employee_id = this.$route.params.employee_id;
		},
		mounted() {
			this.getDetails();
		},
		methods: {
            close() {
                this.dialogBirthDate= false
                this.dialogDateHired= false
				this.dialogAdd 		= false
				this.dialogDelete 	= false
            },
			goto() {
                this.$router.push({ name: "Show-Employee", params: { employee_id: this.employee_id } });
            },
			getDetails() {
				axios.get('/api/get-employee/' + this.employee_id).then(({ data }) => {
					this.employee = data;
				})
				axios.get('/api/get-positions').then(({ data }) => {
          			this.positions = data;

					for(var x = 0; this.positions[x].id != this.employee.position_id; x++) {
						this.employee.basic_salary = null;
					}
					this.employee.basic_salary = this.positions[x].basic_salary;
        		})
				axios.get('/api/get-schedules').then(({ data }) => {
          			this.schedules = data;
        		})
				axios.get('/api/get-benefits').then(({ data }) => {
					this.benefits = data;
					this.form.benefits = this.benefits.map(benefit => {
						return {
							id: '',
							ref_number: '',
							employee_id: this.employee_id
						}
					})
					return this.benefits
				})
			},
			getDetails2() {
				axios.get('/api/get-employee/' + this.getBenefit.pivot.employee_id).then(({ data }) => {
					this.employee = data;
				})
				axios.get('/api/get-positions').then(({ data }) => {
          			this.positions = data;

					for(var x = 0; this.positions[x].id != this.employee.position_id; x++) {
						this.employee.basic_salary = null;
					}
					this.employee.basic_salary = this.positions[x].basic_salary;
        		})
				axios.get('/api/get-schedules').then(({ data }) => {
          			this.schedules = data;
        		})
				axios.get('/api/get-benefits').then(({ data }) => {
					this.benefits = data;
				})
			},
			onSelectPosition(e) {
				for(var x = 0; this.positions[x].id != e; x++) {
					this.employee.basic_salary = null;
				}
				this.employee.basic_salary = this.positions[x].basic_salary;
			},
			getAge() {
				var today = new Date();
				var birthDate = new Date(this.employee.birthdate);
				var age = today.getFullYear() - birthDate.getFullYear();
				var m = today.getMonth() - birthDate.getMonth();
				if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate()))
				{
					age--;
				}
				this.employee.age = age;
			},
			updateBenefitDetails() {
				for(var x = 0; x < this.employee.benefits.length; x++) {
					if (typeof this.employee.benefits[x].id === 'number')
					{
						axios.put('/api/pluck-updated-benefit/' + this.employee.id, {
							id:			this.employee.benefits[x].id,
							ref_number: this.employee.benefits[x].pivot.ref_number
						})
						.then((res) => {
							console.log(res);
						})
					}
					else if (this.employee.benefits[x].id === true)
					{
						axios.put('/api/pluck-updated-benefit/' + this.employee.id, {
							id:			this.benefits[x].id,
							ref_number: this.employee.benefits[x].pivot.ref_number
						})
						.then((res) => {
							console.log(res);
						})
					}
					else
					{
						axios.put('/api/pluck-updated-benefit/' + this.employee.id, {
							id:			this.benefits[x].id,
							ref_number: null
						})
						.then((res) => {
							console.log(res);
						})
					}
				}
			},
            updateEmployee() {
				this.$refs.observer.validate();
				this.getAge();
				axios.post('/api/update-employee', this.employee)
				.then(() => {
						this.updateBenefitDetails();
                        alert('employee updated successfully');
					})
				this.$router.push({ name: "Employee" });
			},
			addBenefit(item) {
				this.getBenefit = Object.assign({}, item)
				this.dialogAdd = true
			},
			addBenefitConfirm() {
				this.$refs.addBenefitObserver.validate();
				axios.post('/api/pluck-add-benefit', this.form.benefits)
					.then(({ data }) => {
						if (data.length == 0)
						{
							alert('benefit successfully added!');
						}
						else
						{
							alert('benefit successfully added, but '+data+' already exist!');
						}
					})
				this.getDetails2();
                this.close();
				// this.reset();
			},
			deleteBenefit(item) {
				this.getBenefit = Object.assign({}, item)
				this.dialogDelete = true
			},
			deleteBenefitConfirm() {
				axios.post('/api/delete-benefit-pivot', this.getBenefit.pivot)
                    .then(() => {
                        alert("employee's benefit deleted successfully");
                    })
                this.getDetails2();
                this.close();
			},
			reset() {
				this.$refs.ref_form.reset();
			}
		}
	}
</script>
