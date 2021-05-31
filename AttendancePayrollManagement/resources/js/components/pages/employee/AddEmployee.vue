<template>
	<div>
		<p class="alert alert-danger" v-if="errors.length">
			<b>Please correct the following error(s):</b>
			<ul class="mb-0">
			<li v-for="(error, index) in errors" :key="index">{{ error }}</li>
			</ul>
		</p>

		<validation-observer v-slot="{ handleSubmit }" ref="observer">
		    <v-card flat id="addEmp">
                <v-card-title>
                    Add Employee
                    <v-spacer></v-spacer>
					<v-btn
						small
						@click.prevent="goto"
					>
						<v-icon left>mdi-arrow-left-bold</v-icon>BACK
					</v-btn>
                </v-card-title>
				<v-form @submit.prevent="handleSubmit(addEmployee)">
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
													v-model="form.first_name"
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
													v-model="form.last_name"
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
												<validation-provider v-slot="{ errors }" name="Contact Number"
													:rules="{ required: true, digits: 11, regex: /^09\d{9}$/ }">
												<v-text-field
													v-model="form.contact_number"
													label="Contact Number"
													:error-messages="errors"
													:counter="11"
													required
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
															v-model="form.birthdate"
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
														v-model="form.birthdate"
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
													v-model="form.address"
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
													v-model="form.gender"
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
													v-model="form.marital_status"
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
													v-model="form.rate_type"
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
													v-model="form.basic_salary"
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
													v-model="form.additional"
													label="Additional Salary"
													:error-messages="errors"
												></v-text-field>
												</validation-provider>
											</v-col>
											<v-col
												cols="12"
												md="6"
											>
												<v-card-text> Total Salary: {{ Number(form.basic_salary) + Number(form.additional) }} </v-card-text>
											</v-col>
										</v-row>
									</v-container>
								</v-container>
							<!-- </v-card> -->
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
															v-model="form.date_hired"
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
														v-model="form.date_hired"
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
													v-model="form.leave_credits"
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
													v-model="form.schedule_id"
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
													v-model="form.position_id"
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
													v-model="form.email"
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
										<h4> Benefits Details </h4>
										<v-divider></v-divider>
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
								</v-container>
						</v-col>
					</v-row>
					<v-btn absolute bottom right type="submit" > Add </v-btn>
				</v-form>
            </v-card>
		</validation-observer>
	</div>
</template>

<script>
	import { ValidationObserver, ValidationProvider, extend, setInteractionMode } from 'vee-validate';
  	import { required, alpha_spaces, alpha_num, digits, email, numeric, regex } from 'vee-validate/dist/rules'

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

		extend('regex', {
			...regex,
			message: '{_field_} {_value_} does not match {regex}',
		})

	export default {
		components: { ValidationObserver, ValidationProvider },
		data() {
			return {
				// DIALOGS
				errors:[],
				enabled: false,
				dialogBirthDate: false,
				dialogDateHired: false,

				benefits: [],
				positions: null,
				schedules: null,

				form: {
					additional: 0,
                    image_path: '',
					benefits: [],
				},

				gender: ['Male', 'Female'],
				rate_type: ['Daily', 'Monthly'],
				marital: ['Single', 'Engaged', 'Married', 'Separated', 'Widowed', 'Divorced']
			}
		},
		mounted() {
			this.getDetails();
		},
		methods: {
            close() {
                this.dialogBirthDate = false
                this.dialogDateHired = false
            },
			goto() {
                this.$router.push({ name: "Employee" });
            },
			onSelectPosition(e) {
				for(var x = 0; this.positions[x].id != e; x++) {
					this.form.basic_salary = null;
				}
				this.form.basic_salary = this.positions[x].basic_salary;
			},
			getAge() {
				var today = new Date();
				var birthDate = new Date(this.form.birthdate);
				var age = today.getFullYear() - birthDate.getFullYear();
				var m = today.getMonth() - birthDate.getMonth();
				if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate()))
				{
					age--;
				}
				this.form.age = age;
			},
			getBenefitDetails(e) {
				for(var x = 0; x < this.form.benefits.length; x++) {
					if (typeof this.form.benefits[x].id === 'number')
					{
						axios.post('/api/pluck-benefit', this.form.benefits[x])
					}
					else
					{
						this.form.benefits[x].id = this.benefits[x].id;
						this.form.benefits[x].ref_number = null;
						axios.post('/api/pluck-benefit', this.form.benefits[x])
					}
				}
			},
			getDetails() {
				axios.get('/api/get-positions').then(({ data }) => {
          			this.positions = data;
        		})
				axios.get('/api/get-schedules').then(({ data }) => {
					this.schedules = data;
        		})
				axios.get('/api/get-benefits').then(({ data }) => {
					this.benefits = data;
					this.form.benefits = this.benefits.map(benefit => {
						return {
							id: '',
							ref_number: ''
						}
					})
					return this.benefits
				})
			},
            addEmployee() {
                this.$refs.observer.validate();
				this.getAge();
				// ADD USER
				axios.post('/api/user/register', this.form)
				.then((res) =>
				{
					if(res.data.error == 'exist')
					{
						alert('email already taken!');
					}
					else
					{
						// ADD EMPLOYEE
						axios.post('/api/add-employee', this.form)
							.then(() => {
								this.getBenefitDetails();
								alert('employee added successfully');
							})
						this.$router.push({ name: "Employee" });
					}
				})
			},
		}
	}
</script>
