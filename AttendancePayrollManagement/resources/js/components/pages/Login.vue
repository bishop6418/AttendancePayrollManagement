<template>
	<v-app id="login">
		<validation-observer v-slot="{ handleSubmit }" ref="observer">
		    <v-card outlined elevation="2" max-width="500px" class="mx-auto my-12">
				<v-container>
                <v-card-title>
                    Login
                    <v-spacer></v-spacer>
					<v-btn
						style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
						@click.prevent="goBack"
					>
						<v-icon title="Back">mdi-arrow-left-bold</v-icon>
					</v-btn>
                </v-card-title>
				<v-divider></v-divider>
				<v-form @submit.prevent="handleSubmit(login)">
					<v-row>
						<v-col
							cols="12"
							md="12"
						>
							<validation-provider v-slot="{ errors }" name="Email" rules="required|email">
							<v-text-field
								v-model="user.email"
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
					<v-row>
						<v-col
							cols="12"
							md="12"
						>
							<validation-provider v-slot="{ errors }" name="Password" rules="required">
							<v-text-field
								v-model="user.password"
								label="Password"
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
  	import { required, email } from 'vee-validate/dist/rules'

	   setInteractionMode('eager')

		extend('required', {
			...required,
			message: '{_field_} can not be empty'
		})

		extend('email', {
			...email,
			message: 'Email must be valid',
		})

	export default {
		components: { ValidationObserver, ValidationProvider },
		data() {
			return {
				showPassword: false,
				dialog: true,
				user: {}
			}
		},
		methods: {
			goBack() {
				window.location.href = '/'
			},
			login() {
				this.$store.dispatch('currentUser/loginUser', this.user);
			}
		}
	}
</script>