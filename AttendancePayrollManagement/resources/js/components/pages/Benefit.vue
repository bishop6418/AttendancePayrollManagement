<template>
    <div >
        <v-card outlined elevation="2">
            <v-card-title>
                Benefits
            
                <v-btn
                    style="padding:4px;height:24px;min-width:4px;margin-left:5px;"
                    @click="dialogAdd = true"
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
                    :items="benefits"
                    :search="search"
                    dark
                >
                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            small
                            @click.prevent="editBenefit(item)"
                            title="edit"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click.prevent="deleteBenefit(item)"
                            title="delete"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                </v-data-table>
            </v-container>

            <!-- ADD BENEFIT DIALOG -->
            <validation-observer v-slot="{ handleSubmit }" ref="addBenefitObserver">
                <v-dialog v-model="dialogAdd" max-width="500px">
                    <v-card flat>
                        <v-container>
                            <v-card outlined elevation="2">
                                <v-container>
                                    <v-form @submit.prevent="handleSubmit(addBenefit)" ref="ref_form">
                                        <v-card-title class="headline">Add Benefit</v-card-title>
                                        <v-divider></v-divider>
                                        <v-card-text>
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12" md="12">
                                                        <validation-provider v-slot="{ errors }" name="Benefit Name" rules="required|alpha_spaces">
                                                            <v-text-field
                                                                v-model="benefit.name"
                                                                label="Benefit Name"
                                                                :error-messages="errors"
                                                            ></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-card-text>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn small type="submit">Add</v-btn>
                                            <v-btn small @click="close">Cancel</v-btn>
                                        </v-card-actions>
                                    </v-form>
                                </v-container>
                            </v-card>
                        </v-container>
                    </v-card>
                </v-dialog>
            </validation-observer>

            <!-- EDIT BENEFIT DIALOG -->
            <validation-observer v-slot="{ handleSubmit }" ref="updateBenefitObserver">
                <v-dialog v-model="dialogEdit" max-width="500px">
                    <v-card flat>
                        <v-container>
                            <v-card outlined elevation="2">
                                <v-container>
                                    <v-form @submit.prevent="handleSubmit(updateBenefit)" ref="ref_form">
                                        <v-card-title class="headline">Edit Benefit</v-card-title>
                                        <v-divider></v-divider>
                                        <v-card-text>
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12" md="12">
                                                        <validation-provider v-slot="{ errors }" name="Benefit Name" rules="required|alpha_spaces">
                                                            <v-text-field
                                                                v-model="getBenefit.name"
                                                                label="Benefit Name"
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

            <!-- DELETE BENEFIT DIALOG -->
            <v-dialog v-model="dialogDelete" max-width="500px">
                <v-card flat>
                    <v-container>
                        <v-card outlined elevation="2">
                            <v-container>
                                <v-card-title class="headline justify-center">Are you sure to delete this benefit?</v-card-title>
                                <v-card-text class="text-uppercase">
                                    <v-container>
                                        <v-row class="text-center">
                                            <v-col cols="12" md="12">
                                                <p class="font-weight-bold"> {{ getBenefit.name }} </p>
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
                    { text: 'BENEFIT NAME', value: 'name' },
                    { text: 'ACTIONS',      value: 'actions', sortable: false }
                ],
                benefits: [],
                benefit: {},
                getBenefit: {},

                // DIALOGS
                dialogAdd: false,
                dialogEdit: false,
                dialogDelete: false,

                search: '',
            }
        },

        mounted(){
            this.getBenefits();
        },

        methods: {
            close() {
                this.dialogAdd = false
                this.dialogEdit = false
                this.dialogDelete = false
            },

            getBenefits() {
				axios.get('/api/get-benefits').then(({ data }) => {
          			this.benefits = data;
        		})
			},

            reset() {
				this.$refs.ref_form.reset();
			},

            addBenefit() {
                this.$refs.addBenefitObserver.validate();
				axios.post('/api/add-benefit', this.benefit)
                    .then((res) => {
                        console.log(res);
                        alert('benefit added successfully');
					})
                this.getBenefits();
                this.close();
                this.reset();
			},

            editBenefit(item) {
                this.getBenefit = Object.assign({}, item)
                this.dialogEdit = true
            },

			updateBenefit() {
                this.$refs.updateBenefitObserver.validate();
				axios.put('/api/update-benefit/' + this.getBenefit.id, {
                        name: this.getBenefit.name
                    }).then((res) => {
                        console.log(res);
                        alert('benefit updated successfully');
                    })
                this.getBenefits();
                this.close();
			},

            deleteBenefit(item) {
                this.getBenefit = Object.assign({}, item)
                this.dialogDelete = true
            },

            deleteConfirm() {
				axios.delete('/api/delete-benefit/' + this.getBenefit.id)
                    .then((res) => {
                        console.log(res);
                        alert('benefit deleted successfully');
                    })
                this.getBenefits();
                this.close();
			}
        }
    }
</script>