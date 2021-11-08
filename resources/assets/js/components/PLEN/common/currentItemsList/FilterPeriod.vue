<template>
  <b-button-group>
    <b-button class="btn-period" variant="link" size="sm" @click="onClickPeriodToday" :pressed="activePeriod['today']">Hoy</b-button>
    <b-button class="btn-period" variant="link" size="sm" @click="onClickPeriodThisWeek" :pressed="activePeriod['week']">Esta semana</b-button>
    <b-button class="btn-period" variant="link" size="sm" @click="onClickPeriodThisMonth" :pressed="activePeriod['month']">Este mes</b-button>
    <b-button class="btn-period" variant="link" size="sm" @click="onClickPeriodThisYear" :pressed="activePeriod['year']">Este año</b-button>
  </b-button-group>
</template>

<script>
  export default {
    data() {
      return {
        activePeriod: {
          'today': true,
          'week': false,
          'month': false,
          'year': false
        },
      }
    },
    methods: {
      deactivateAllPeriods() {
        const keys = Object.keys(this.activePeriod);
        keys.map( (val) => {
          this.activePeriod[val] = false;
        });
      },
      onClickPeriodThisMonth() {
        this.setActivePeriod('month');
      },
      onClickPeriodThisWeek() {
        this.setActivePeriod('week');
      },
      onClickPeriodThisYear() {
        this.setActivePeriod('year');
      },
      onClickPeriodToday() {
        this.setActivePeriod('today');
      },

      setActivePeriod(period) {
        this.deactivateAllPeriods();
        this.activePeriod[period] = true;
      }
    }
  }
</script>
