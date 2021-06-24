import Vue from "vue";

const moment = require("moment");
const filesize = require("filesize");

const formatter = new Intl.NumberFormat("en-US", {
  style: "currency",
  currency: "USD",
  minimumFractionDigits: 2,
});

Vue.filter("format", function (value) {
  if (!value) return value;

  // const timeZone = new Date(value).toString().split(" ")[5];

  return `${moment.utc(value).local().format("MMMM Do YYYY, h:mm a")}`;
});

Vue.filter("formatDecimals", function (value) {
  return value.toFixed(2);
});

Vue.filter("removeDecimals", function (value) {
  return value.toFixed(0);
});

Vue.filter("formatLabel", function (value) {
  if (!value) {
    return "-";
  }

  if (value === "ActivationFailure") {
    return "Activation Failure";
  } else if (value === "WaitingForCluster") {
    return "Waiting for Cluster";
  } else {
    return value.split("_").join(" ").split("-").join(" ");
  }
});

Vue.filter("capitalize", function (value) {
  if (!value) {
    return value;
  }

  value = value.toString();

  return value
    .toLowerCase()
    .split(" ")
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
    .join(" ");
});

Vue.filter("usFormatNumber", function (value) {
  if (!value) return value;

  const cleaned = ("" + value).replace(/\D/g, "");
  const match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);

  if (match) {
    return "(" + match[1] + ") " + match[2] + "-" + match[3];
  }

  return null;
});

Vue.filter("usFormatCurrency", function (value) {
  if (value || value === 0) {
    return formatter.format(value);
  }

  return value;
});

Vue.filter("date", function (value) {
  if (!value) return value;

  return `${moment(value).format("MM/DD/YYYY")}`;
});

Vue.filter("time", function (value) {
  if (!value) return value;

  return `${moment(value).format("h:mm:ss A")}`;
});

Vue.filter("formatNumbers", function (value) {
  return value ? value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : value;
});

Vue.filter("split", function (value) {
  return value ? value.split("/")[1] : value;
});

Vue.filter("trim", function (name) {
  if (name && name.length && name.length > 20) {
    return name.substring(0, 20) + "...";
  }

  return name;
});

Vue.filter("bytes", function (value) {
  return filesize(value, { symbols: { B: "bytes" } });
});

Vue.filter("unitConverter", function (value, unit) {
  let filter;

  if (value !== undefined) {
    if (unit === "msg/s") {
      filter = value + " msg/s";
    } else {
      filter = filesize(value, { symbols: { B: "bytes" } });

      if (unit === "/s") {
        filter = filter + "/s";
      }
    }
  }

  return filter;
});

Vue.filter("formatStatus", function (value) {
  let filter;

  if (value !== undefined) {
    if (value === "UpdateRequested") {
      filter = "Update Requested";
    } else if (value === "DeletionRequested") {
      filter = "Deletion Requested";
    } else if (value === "ActivationFailure") {
      filter = "Activation Failure";
    } else if (value === "UpdateFailure") {
      filter = "Update Failure";
    } else if (value === "DeletionFailure") {
      filter = "Deletion Failure";
    } else if (value === "NoPolicyFound") {
      filter = "No Policy Found";
    } else if (value === "WaitingForCluster") {
      filter = "Waiting For Cluster";
    } else {
      filter = value;
    }
  } else {
    filter = "-";
  }

  return filter;
});

Vue.filter("camel2title", (camelCase) =>
  camelCase
    .replace(/([A-Z])/g, (match) => ` ${match}`)
    .replace(/^./, (match) => match.toUpperCase())
    .trim()
);

Vue.filter("split", function (value) {
  if (!value) {
    return value;
  }

  value = value.toString();

  return value.split("_").join(" ");
});
