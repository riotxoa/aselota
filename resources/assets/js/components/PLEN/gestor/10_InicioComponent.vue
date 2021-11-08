<template>
  <b-row class="p-5">
    <b-col cols="12" md="6" lg="5">
      <CurrentItemsList />
    </b-col>
    <b-col cols="12" md="6" lg="7">
      <TodayTasks />
      <Calendar />
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
