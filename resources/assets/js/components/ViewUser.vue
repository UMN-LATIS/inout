<template>
  <div>
  	<div class="row">
  		<div class="col-sm-3"><b>Email:</b> <a v-bind:href="'mailto:' + user.email">{{user.email}}</a></div>
    </div>
    <div class="row" v-if="user.phone">  		
      <div class="col-sm-3"><b>Phone:</b> {{user.phone | phone}}</div>
    </div>
    <div class="row" v-if="user.office">
  		<div class="col-sm-6"><b>Office:</b> {{user.office}}</div>
  	</div>
    <div class="row">
      <div class="col-sm-3"><a v-bind:href="user.calendar_link" v-if="user.calendar_link">Google Calendar</a></div>
    </div>
</div>
</template>

<script>
export default {

  name: 'ViewUser',
  props: ['user'],
  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>