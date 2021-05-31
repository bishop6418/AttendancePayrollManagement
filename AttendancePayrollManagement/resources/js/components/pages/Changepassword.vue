<template>
	<v-app id="register">
		<p class="alert alert-danger" v-if="errors.length">
			<b>Please correct the following error(s):</b>
			<ul class="mb-0">
				<li v-for="(error, index) in errors" :key="index">{{ error }}</li>
			</ul>
		</p>
		<validation-observer v-slot="{ handleSubmit }" ref="observer">
		    <v-card outlined elevation="2" max-width="500px" class="mx-auto my-12">
				<v-container>
                <v-card-title>
                    Change Password
                    <v-spacer></v-spacer>
                </v-card-title>
				<v-divider></v-divider>
				<v-form @submit.prevent="handleSubmit(login)">
					<v-row>
						<v-col
							cols="12"
							md="12"
						>
							<validation-provider v-slot="{ errors }" name="Password" rules="required" vid="password">
							<v-text-field
								v-model="user.old_password"
								label="Old Password"
								:type="showPassword ? 'text' : 'password'"
								:error-messages="errors"
								:append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
								@click:append="showPassword = !showPassword"
							>
								<v-icon
									slot="prepend"
								>
									mdi-lock
								</v-icon>
							</v-text-field>
							</validation-provider>
						</v-col>
						<v-col
							cols="12"
							md="12"
						>
							<validation-provider v-slot="{ errors }" name="Password" rules="required|confirmed:confirm" vid="password">
							<v-text-field
								v-model="user.new_password"
								label="New Password"
								:type="showPassword ? 'text' : 'password'"
								:error-messages="errors"
								:append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
								@click:append="showPassword = !showPassword"
							>
								<v-icon
									slot="prepend"
								>
									mdi-lock
								</v-icon>
							</v-text-field>
							</validation-provider>
						</v-col>
						<v-col
							cols="12"
							md="12"
						>
							<validation-provider v-slot="{ errors }" name="Confirm Password" rules="required|confirmed:password" vid="confirm">
							<v-text-field
								v-model="user.password_confirmation"
								label="Confirm Password"
								:type="showConfirmPassword ? 'text' : 'password'"
								:error-messages="errors"
								:append-icon="showConfirmPassword ? 'mdi-eye' : 'mdi-eye-off'"
								@click:append="showConfirmPassword = !showConfirmPassword"
							>
								<v-icon
									slot="prepend"
								>
									mdi-lock
								</v-icon>
							</v-text-field>
							</validation-provider>
						</v-col>
					</v-row>
					<v-card-actions>
						<v-spacer></v-spacer>
						<v-btn type="submit" > Submit </v-btn>
					</v-card-actions>
				</v-form>
				</v-container>
            </v-card>
		</validation-observer>
	</v-app>
</template>

<script>
	import { ValidationObserver, ValidationProvider, extend, setInteractionMode } from 'vee-validate';
  	import { required, email, alpha_spaces, confirmed } from 'vee-validate/dist/rules'

	   setInteractionMode('eager')

		extend('required', {
			...required,
			message: '{_field_} can not be empty'
		})
		extend('alpha_spaces', {
			...alpha_spaces,
			message: '{_field_} may only contain alphabetic characters as well as spaces'
		})

		extend('confirmed', {
			...confirmed,
			message: '{_field_} does not match'
		})

	export default {
		components: { ValidationObserver, ValidationProvider },
		data() {
			return {
				showPassword: false,
				showConfirmPassword: false,
				dialog: true,
				user: {},
        errors:[]
			}
		},
		methods: {
		// 	login() {
		// 		// this.$store.dispatch('currentUser/loginUser', this.user);
		// 		const data =
		// 			{

		// 				new_password:		this.user.new_password,
		// 				old_password:	this.user.old_password
		// 			}
		// 		axios.post('/api/user/change-password', data)
		// 			.then((response) =>
		// 			{
		// 				console.log(response.data);
		// 				// if(response.data.access_token)
		// 				// {
		// 				// 	localStorage.setItem('access_token', response.data.access_token);
		// 				// }
		// 				// window.location.replace('/user');
		// 			})
        //    .catch(error =>{
        //       this.errors.push(error.response.data.error);
        //     })
		// 	}
		}
	}
</script>
