<template>
    <v-app>
		<app-header-sidebar></app-header-sidebar>
		<app-content></app-content>
	</v-app>
</template>

<script>
	import AppHeaderSidebar from './HeaderSidebar'
	import AppContent from './Contents'

	export default {
		components: {
			AppHeaderSidebar,
			AppContent
		}
	}
</script>