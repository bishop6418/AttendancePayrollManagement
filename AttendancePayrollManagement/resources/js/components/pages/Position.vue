<template>
    <div >
        <v-card outlined elevation="2">
            <v-card-title>
                Positions
            
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
                    :items="positions"
                    :search="search"
                    dark
                >
                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            small
                            @click.prevent="editPosition(item)"
                            title="edit"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click.prevent="deletePosition(item)"
                            title="delete"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                </v-data-table>
            </v-container>

            <!-- ADD POSITION DIALOG -->
            <validation-observer v-slot="{ handleSubmit }" ref="addPositionObserver">
                <v-dialog v-model="dialogAdd" max-width="500px">
                    <v-card flat>
                        <v-container>
                            <v-card outlined elevation="2">
                                <v-container>
                                    <v-form @submit.prevent="handleSubmit(addPosition)" ref="ref_form">
                                        <v-card-title class="headline">Add Position</v-card-title>
                                        <v-divider></v-divider>
                                        <v-card-text>
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12" md="12">
                                                        <validation-provider v-slot="{ errors }" name="Position Name" rules="required|alpha_spaces">
                                                            <v-text-field
                                                                v-model="position.name"
                                                                label="Position Name"
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

            <!-- EDIT POSITION DIALOG -->
            <validation-observer v-slot="{ handleSubmit }" ref="updatePositionObserver">
                <v-dialog v-model="dialogEdit" max-width="500px">
                    <v-card flat>
                        <v-container>
                            <v-card outlined elevation="2">
                                <v-container>
                                    <v-form @submit.prevent="handleSubmit(updatePosition)" ref="ref_form">
                                        <v-card-title class="headline">Edit Position</v-card-title>
                                        <v-divider></v-divider>
                                        <v-card-text>
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12" md="12">
                                                        <validation-provider v-slot="{ errors }" name="Position Name" rules="required|alpha_spaces">
                                                            <v-text-field
                                                                v-model="getPosition.name"
                                                                label="Position Name"
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

            <!-- DELETE POSITION DIALOG -->
            <v-dialog v-model="dialogDelete" max-width="500px">
                <v-card flat>
                    <v-container>
                        <v-card outlined elevation="2">
                            <v-container>
                                <v-card-title class="headline justify-center">Are you sure to delete this position?</v-card-title>
                                <v-card-text class="text-uppercase">
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" md="12">
                                                <label> Position Name: </label>
                                                <p class="font-weight-bold"> {{ getPosition.name }} </p>
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
                    { text: 'POSITION NAME',value: 'name' },
                    { text: 'ACTIONS',      value: 'actions', sortable: false },
                ],
                positions: [],
                position: {},
                getPosition: {},

                // DIALOGS
                dialogAdd: false,
                dialogEdit: false,
                dialogDelete: false,

                search: '',
            }
        },

        mounted(){
            this.getPositions();
        },

        methods: {
            close() {
                this.dialogAdd = false
                this.dialogEdit = false
                this.dialogDelete = false
            },

            getPositions() {
				axios.get('/api/get-positions').then(({ data }) => {
          			this.positions = data;
        		})
			},

            reset() {
				this.$refs.ref_form.reset();
			},

            addPosition() {
                this.$refs.addPositionObserver.validate();
				axios.post('/api/add-position', this.position)
                    .then(() => {
                        alert('position added successfully');
					})
                this.getPositions();
                this.close();
                this.reset();
			},

            editPosition(item) {
                this.getPosition = Object.assign({}, item)
                this.dialogEdit = true
            },

			updatePosition() {
                this.$refs.updatePositionObserver.validate();
				axios.put('/api/update-position/' + this.getPosition.id, {
                        name: this.getPosition.name
                    }).then(() => {
                        alert('position updated successfully');
                    })
                this.getPositions();
                this.close();
			},

            deletePosition(item) {
                this.getPosition = Object.assign({}, item)
                this.dialogDelete = true
            },

            deleteConfirm() {
				axios.delete('/api/delete-position/' + this.getPosition.id)
                    .then(() => {
                        alert('position deleted successfully');
                    })
                this.getPositions();
                this.close();
			}
        }
    }
</script>