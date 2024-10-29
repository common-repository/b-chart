let bchartSeletor = document.querySelectorAll(".bChart");

Object.values(bchartSeletor).map((chartItem) => {
  let bchart = chartItem.getContext("2d");
  let datasets = [];
  let bchartData = chartItem.getAttribute("data-bchart");
  chartItem.removeAttribute("data-bchart");

  const bchartData_obj = JSON.parse(bchartData);
  initializeChart(bchart, bchartData_obj);
});

function initializeChart(bchart, bchartData_obj) {
  if (!bchartData_obj) {
    return false;
  }
  // Chart Option key
  const {
    chart_type,
    chart_grid_color,
    chart_label_color,
    chart_point_style,
    pointer_size,
    pointer_hover_radius,
    chart_title_opt,
    chart_subtitle_opt,
    chart_title,
    chart_subtitle,
    title_font_size,
    subtitle_font_size,
    title_color,
    subtitle_color,
    legend_position,
    legend_color,
    legend_pstyle,
    animation_type,
    animation_duration,
    animation_tension,
    animation_loop,
    chart_opt_special,
  } = bchartData_obj;

  if (!["bar", "line", "pie"].includes(chart_type)) return false;

  // Data
  let labelName = bchartData_obj.chart_label_name;
  let labels = labelName.split(",");

  if (chart_type == "bar" || chart_type == "line" || chart_type == "radar") {
    datasets = getBarLineDataset(bchartData_obj);
  } else {
    const { labels: spcialLabel, dataSets: specialDataset } =
      getSpecialDatasets(bchartData_obj);
    labels = spcialLabel.filter((label) => label != "");
    datasets = specialDataset;
  }

  const animations = {
    tension: {
      duration: animation_duration,
      easing: animation_type,
      from: 0,
      to: animation_loop === "1" ? animation_tension : 0,
      loop: animation_loop === "1",
    },
  };

  var myChart = new Chart(bchart, {
    type: chart_type || "line",
    data: {
      labels,
      datasets,
    },
    options: {
      responsive: true,
      animations: animation_loop == "1" ? animations : {},
      plugins: {
        legend: {
          position: legend_position || "top",
          labels: {
            usePointStyle: legend_pstyle === "1",
            color: legend_color,
          },
        },
        title: {
          display: chart_title_opt === "1",
          text: chart_title,
          color: title_color,
          font: {
            size: title_font_size || "40",
            family: "Roboto",
          },
          padding: {
            bottom: 10,
          },
        },
        subtitle: {
          display: chart_subtitle_opt === "1",
          text: chart_subtitle,
          color: subtitle_color,
          font: {
            size: subtitle_font_size || "20",
            family: "Roboto",
          },
          padding: {
            bottom: 15,
          },
        },
        tooltip: {
          position: "nearest",
          usePointStyle: true,
        },
      },

      scales: {
        x: {
          ticks: {
            color: chart_label_color,
          },
          grid: {
            color: chart_grid_color,
          },
        },
        y: {
          beginAtZero: true,
          ticks: {
            color: chart_label_color,
          },
          grid: {
            color: chart_grid_color,
          },
        },
      },
    },
  });
}

function getBarLineDataset(bchartData_obj) {
  const dataSets = [];
  const {
    chart_type,
    chart_point_style,
    chart_tension,
    pointer_size,
    pointer_hover_radius,
    data_chart_opt,
  } = bchartData_obj;

  if (!["bar", "line", "radar"].includes(chart_type)) return false;

  Object.values(data_chart_opt).map((data_item) => {
    data = [];
    dataBackground = [];
    dataBorder = [];
    if (data_item.chart_datas) {
      Object.values(data_item.chart_datas).map((item) => {
        data.push(item.data);
        dataBackground.push(data_item?.data_bg);
        dataBorder.push(data_item?.data_bder_color);
      });
    }

    dataSets.push({
      data,
      backgroundColor: dataBackground,
      borderColor: dataBorder,
      borderWidth: parseInt(data_item.chart_border),
      label: data_item.chart_label,
      tension: chart_tension,
      pointStyle: chart_point_style,
      hoverBorderWidth: parseInt(data_item.chart_border),
      pointRadius: pointer_size,
      pointHoverRadius: pointer_hover_radius,
    });
  });

  return dataSets;
}

function getSpecialDatasets(bchartData_obj) {
  const dataSets = [];
  labels = [];
  let dataBoxs = [];
  let dataBackground = [];
  let dataBorder = [];

  const { chart_type, chart_opt_special, chart_point_style } = bchartData_obj;

  if (["bar", "line", "radar"].includes(chart_type)) return false;

  Object.values(chart_opt_special).map((item) => {
    labels.push(item?.data_label);
    dataBackground.push(item?.data_bg);
    dataBorder.push(item?.data_bder_color);

    Array.isArray(item?.data_list) &&
      item.data_list.map((data, index) => {
        if (dataBoxs[index]) {
          dataBoxs[index].push(data.data);
        } else {
          dataBoxs[index] = [data.data];
        }
      });
    // dataBoxs.push(dataBox);
  });

  dataBoxs.map((data) => {
    dataSets.push({
      data,
      backgroundColor: dataBackground,
      borderColor: dataBorder,
      // borderWidth: parseInt(data_item.chart_border),
      // label: data_item.chart_label,
      pointStyle: chart_point_style,
    });
  });

  return { labels, dataSets };
}
