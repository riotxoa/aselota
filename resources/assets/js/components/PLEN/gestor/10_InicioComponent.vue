<template>
  <b-row class="p-5">
    <b-col cols="12" md="6">
      <Calendar class="mr-md-1" />
    </b-col>
    <b-col cols="12" md="6">
      <TodayTasks class="mt-3 mt-md-0" />
      <CurrentItemsList class="mt-3"/>
    </b-col>
  </b-row>
</template>

<script>
  import Calendar from '../common/calendar/CalendarComponent';
  import CurrentItemsList from '../common/currentItemsList/CurrentItemsListComponent';
  import TodayTasks from '../common/todayTasks/TodayTasksComponent';

  export default {
    data() {
      return {
      }
    },
    created() {
    },
    components: {
      Calendar,
      CurrentItemsList,
      TodayTasks
    }
  }
</script>

<style focused>
</style>
