import { FC, useState, useEffect } from 'react';
import ButtonGroup from 'components/common/ButtonGroup';
import { UsDollarFormatter } from 'utils/helper';
import { ScriptableContext } from 'chart.js';
import { IFilter, IRevenueData } from 'interface/dashboardInterface';
import _ from 'lodash';
import { Line } from 'react-chartjs-2';
import { filledLineChartoptions } from './LineChart/constant';
import { Loader } from 'components/common/loader';
import dashboardService from 'services/dashboardService';
import { generateDaySlots, getDateRangeCategory } from './constant';
import dayjs, { Dayjs } from 'dayjs';
import {
  getNetSalesRevenueEP,
  getTotalSalesRevenueEP,
} from 'services/constants';
import { useAppSelector } from 'hooks/reduxHooks';

interface IProps {
  filter: IFilter;
}
interface IDatasets {
  total: number[];
  direct: number[];
  initial: number[];
  recurring: number[];
  salvage: number[];
  upsell: number[];
}

interface IPlaceholderData {
  Range: string;
  DirectSale: number;
  UpsellSale: number;
  InitialSale: number;
  RecurringSale: number;
  SalvageSale: number;
  Total: number;
  Date: Dayjs;
}

interface IFormattedSalesRevenueData {
  chart: {
    topContainer: {
      legend: string;
      total: number;
      borderColor?: string;
    }[];
    labels: string[];
    datasets: {
      label: string;
      borderColor: string;
      backgroundColor: (
        _context: ScriptableContext<'line'>,
      ) => CanvasGradient | string;
      legend: string;
      data: number[];
      total: number;
      OverallTotal: number[];
      pointBorderColor: string;
      pointBackgroundColor: string;
      fill: boolean;
      hidden?: boolean;
    }[];
  };
}

const revenueTypes = {
  grossRevenue: 'Total Revenue',
  // netRevenue: 'Net Revenue', temporarily hiding
};
const filterButtons = Object.values(revenueTypes).map(types => ({
  name: types,
}));

const SalesRevenue: FC<IProps> = ({ filter }) => {
  const [revenueType, setRevenueType] = useState(revenueTypes.grossRevenue);
  const { currentNewYorkTime } = useAppSelector(app => app.newYorkDateTime);
  const hourlyPlaceholderData: IPlaceholderData[] = Array(24)
    .fill('')
    .map((_, index) => {
      const time = index % 12 === 0 ? 12 : index % 12;
      const rem = index / 12;
      const suffix = rem < 1 ? 'AM' : 'PM';
      return {
        Range: `${time}${suffix}`,
        DirectSale: 0,
        InitialSale: 0,
        SalvageSale: 0,
        RecurringSale: 0,
        UpsellSale: 0,
        Total: 0,
        Date: dayjs(currentNewYorkTime).tz(),
      };
    });
  const [totalSalesRevenueData, setTotalSalesRevenueData] = useState<
    IRevenueData[]
  >([]);
  const [netSalesRevenueData, setNetSalesRevenueData] = useState<
    IRevenueData[]
  >([]);
  const [isLoading, setLoading] = useState(false);
  const [canApiCall, setCanApiCall] = useState({});
  const [formattedSalesRevenueData, setFormattedSalesRevenueData] =
    useState<IFormattedSalesRevenueData>({
      chart: { datasets: [], labels: [], topContainer: [] },
    });

  const fetchSalesRevenue = async () => {
    if (
      (totalSalesRevenueData.length > 0 && netSalesRevenueData.length > 0) ||
      isLoading
    ) {
      return;
    }
    setLoading(true);
    const payload = {
      StartDate: filter.start_time?.format('YYYY-MM-DD') ?? '',
      EndDate: filter.end_time?.format('YYYY-MM-DD') ?? '',
      ClientIds: filter.client.map(item => item.value),
      StoreIds: filter.store.map(item => item.value),
      GroupBy: getDateRangeCategory(filter.start_time, filter.end_time),
    };
    const endpoint =
      revenueType === revenueTypes.grossRevenue
        ? getTotalSalesRevenueEP
        : getNetSalesRevenueEP;
    const res = await dashboardService.getSalesRevenue(payload, endpoint);
    if (res?.data?.Result) {
      if (revenueType === revenueTypes.grossRevenue) {
        setTotalSalesRevenueData(res.data.Result);
      } else {
        setNetSalesRevenueData(res.data.Result);
      }
    }
    setLoading(false);
  };

  useEffect(() => {
    if (filter.client.length > 0 && filter.store.length > 0) {
      fetchSalesRevenue();
    } else {
      setTotalSalesRevenueData([]);
      setNetSalesRevenueData([]);
    }
  }, [filter, revenueType, canApiCall]);

  useEffect(() => {
    setRevenueType(revenueTypes.grossRevenue);
    setNetSalesRevenueData([]);
    setTotalSalesRevenueData([]);
    setCanApiCall({});
  }, [filter]);

  const generateTimeSlotsForDay = (date: Dayjs) => {
    const slots = _.cloneDeep(hourlyPlaceholderData);
    slots.forEach(slot => {
      slot.Date = date;
      slot.Range = `${date.format('MM/DD')} ${slot.Range}`;
    });
    return slots;
  };

  const handleFormatRevenueData = (revenueData: IRevenueData[]) => {
    const dateRange = getDateRangeCategory(filter.start_time, filter.end_time);
    if (dateRange === 'hour') {
      const dayDiff = filter.end_time?.diff(filter.start_time, 'day') ?? 0;
      const currentDate = filter.start_time as Dayjs;
      const placeholderData = [];
      for (let day = 0; day < dayDiff + 1; day++) {
        placeholderData.push(
          ...generateTimeSlotsForDay(currentDate.add(day, 'day')),
        );
      }
      placeholderData.forEach(data => {
        const revenue = revenueData.find(item => {
          return (
            item.Range.includes(data.Range) &&
            item.Range.split(' ')[0] === data.Date.format('MM/DD')
          );
        });
        if (revenue) {
          const {
            DirectSale,
            InitialSale,
            RecurringSale,
            SalvageSale,
            UpsellSale,
          } = revenue;
          data.DirectSale = parseFloat(Number(DirectSale).toFixed(2));
          data.RecurringSale = parseFloat(Number(RecurringSale).toFixed(2));
          data.InitialSale = parseFloat(Number(InitialSale).toFixed(2));
          data.SalvageSale = parseFloat(Number(SalvageSale).toFixed(2));
          data.UpsellSale = parseFloat(Number(UpsellSale).toFixed(2));
          data.Total = parseFloat(
            Number(
              DirectSale +
                RecurringSale +
                InitialSale +
                SalvageSale +
                UpsellSale,
            ).toFixed(2),
          );
        }
      });
      return placeholderData;
    } else if (dateRange === 'day') {
      const placeholderData = generateDaySlots(
        filter.start_time as Dayjs,
        filter.end_time as Dayjs,
        {
          DirectSale: 0,
          InitialSale: 0,
          SalvageSale: 0,
          RecurringSale: 0,
          UpsellSale: 0,
          Total: 0,
        },
      );
      placeholderData.forEach(data => {
        const revenue = revenueData.find(item => {
          return item.Range.includes(data.Range);
        });
        if (revenue) {
          const {
            DirectSale,
            InitialSale,
            RecurringSale,
            SalvageSale,
            UpsellSale,
          } = revenue;
          data.DirectSale = parseFloat(Number(DirectSale).toFixed(2));
          data.RecurringSale = parseFloat(Number(RecurringSale).toFixed(2));
          data.InitialSale = parseFloat(Number(InitialSale).toFixed(2));
          data.SalvageSale = parseFloat(Number(SalvageSale).toFixed(2));
          data.UpsellSale = parseFloat(Number(UpsellSale).toFixed(2));
          data.Total = parseFloat(
            Number(
              DirectSale +
                RecurringSale +
                InitialSale +
                SalvageSale +
                UpsellSale,
            ).toFixed(2),
          );
        }
      });
      return placeholderData;
    } else {
      const formattedData = revenueData.map(item => ({
        DirectSale: parseFloat(Number(item.DirectSale).toFixed(2)),
        RecurringSale: parseFloat(Number(item.RecurringSale).toFixed(2)),
        InitialSale: parseFloat(Number(item.InitialSale).toFixed(2)),
        SalvageSale: parseFloat(Number(item.SalvageSale).toFixed(2)),
        UpsellSale: parseFloat(Number(item.UpsellSale).toFixed(2)),
        Total: parseFloat(
          Number(
            item.DirectSale +
              item.RecurringSale +
              item.InitialSale +
              item.SalvageSale +
              item.UpsellSale,
          ).toFixed(2),
        ),
        Range: item.Range,
      }));
      return formattedData;
    }
  };

  useEffect(() => {
    const values: IDatasets = {
      total: [],
      direct: [],
      initial: [],
      recurring: [],
      salvage: [],
      upsell: [],
    };
    const salesRevenueData =
      revenueType === revenueTypes.grossRevenue
        ? totalSalesRevenueData
        : netSalesRevenueData;
    const formattedData = handleFormatRevenueData(salesRevenueData);
    values.direct = formattedData.map(item => item.DirectSale);
    values.initial = formattedData.map(item => item.InitialSale);
    values.recurring = formattedData.map(item => item.RecurringSale);
    values.salvage = formattedData.map(item => item.SalvageSale);
    values.upsell = formattedData.map(item => item.UpsellSale);
    values.total = formattedData.map(item => item.Total);

    const data = {
      chart: {
        topContainer: [
          {
            legend: 'Total',
            total: _.sum(values.total),
          },
          {
            legend: 'Direct',
            borderColor: '#F90182',
            total: _.sum(values.direct),
          },
          {
            legend: 'Initial',
            borderColor: '#6AD2FF',
            total: _.sum(values.initial),
          },
          {
            legend: 'Recurring',
            borderColor: '#C237F3',
            total: _.sum(values.recurring),
          },
          {
            legend: 'Salvage',
            borderColor: '#F36337',
            total: _.sum(values.salvage),
          },
          {
            legend: 'Upsell',
            borderColor: '#05CD99',
            total: _.sum(values.upsell),
          },
        ],
        labels: formattedData.map(data => data.Range),
        datasets: [
          {
            label: 'Direct',
            borderColor: '#F90182',
            backgroundColor: (context: ScriptableContext<'line'>) => {
              const ctx = context.chart.ctx;
              const gradient = ctx.createLinearGradient(0, 0, 0, 200);
              gradient.addColorStop(0, 'rgba(249, 1, 130, 0.5)');
              gradient.addColorStop(1, 'rgba(249, 1, 130, 0.1)');
              return gradient;
            },
            legend: 'Direct',
            data: values.direct,
            total: _.sum(values.direct),
            OverallTotal: values.total,
            pointBorderColor: 'white',
            pointBackgroundColor: '#F90182',
            fill: true,
            hidden: false,
          },
          {
            label: 'Initial',
            borderColor: '#6AD2FF',
            backgroundColor: (context: ScriptableContext<'line'>) => {
              const ctx = context.chart.ctx;
              const gradient = ctx.createLinearGradient(0, 0, 0, 200);
              gradient.addColorStop(0, 'rgba(106, 210, 255, 0.5)');
              gradient.addColorStop(1, 'rgba(106, 210, 255, 0.1)');
              return gradient;
            },
            legend: 'Initial',
            data: values.initial,
            total: _.sum(values.initial),
            OverallTotal: values.total,
            pointBorderColor: 'white',
            pointBackgroundColor: '#6AD2FF',
            fill: true,
            hidden: false,
          },
          {
            label: 'Recurring',
            borderColor: '#C237F3',
            backgroundColor: (context: ScriptableContext<'line'>) => {
              const ctx = context.chart.ctx;
              const gradient = ctx.createLinearGradient(0, 0, 0, 200);
              gradient.addColorStop(0, 'rgba(194, 55, 243, 0.5)');
              gradient.addColorStop(1, 'rgba(194, 55, 243, 0.1)');
              return gradient;
            },
            legend: 'Recurring',
            data: values.recurring,
            total: _.sum(values.recurring),
            OverallTotal: values.total,
            pointBorderColor: 'white',
            pointBackgroundColor: '#C237F3',
            fill: true,
            hidden: false,
          },
          {
            label: 'Salvage',
            borderColor: '#F36337',
            backgroundColor: (context: ScriptableContext<'line'>) => {
              const ctx = context.chart.ctx;
              const gradient = ctx.createLinearGradient(0, 0, 0, 200);
              gradient.addColorStop(0, 'rgba(243, 99, 55, 0.5)');
              gradient.addColorStop(1, 'rgba(243, 99, 55, 0.1)');
              return gradient;
            },
            legend: 'Salvage',
            data: values.salvage,
            total: _.sum(values.salvage),
            OverallTotal: values.total,
            pointBorderColor: 'white',
            pointBackgroundColor: '#F36337',
            fill: true,
            hidden: false,
          },
          {
            label: 'Upsell',
            borderColor: '#05CD99',
            backgroundColor: (context: ScriptableContext<'line'>) => {
              const ctx = context.chart.ctx;
              const gradient = ctx.createLinearGradient(0, 0, 0, 200);
              gradient.addColorStop(0, 'rgba(5, 205, 153, 0.5)');
              gradient.addColorStop(1, 'rgba(5, 205, 153, 0.1)');
              return gradient;
            },
            legend: 'Upsell',
            data: values.upsell,
            total: _.sum(values.upsell),
            OverallTotal: values.total,
            pointBorderColor: 'white',
            pointBackgroundColor: '#05CD99',
            fill: true,
            hidden: false,
          },
        ],
      },
    };
    setFormattedSalesRevenueData(data);
  }, [totalSalesRevenueData, netSalesRevenueData, revenueType]);

  const toggleDataset = (index: number) => {
    if (index < 0) return;
    setFormattedSalesRevenueData(prevData => {
      const newDatasets = [...prevData.chart.datasets];
      const { OverallTotal, data } = newDatasets[index];
      const hidden = !newDatasets[index].hidden;
      newDatasets[index].hidden = hidden;
      const updatedOverallTotal = OverallTotal.map((total, totalIndex) =>
        parseFloat(
          (hidden
            ? total - data[totalIndex]
            : total + data[totalIndex]
          ).toFixed(2),
        ),
      );
      newDatasets.forEach(item => {
        item.OverallTotal = updatedOverallTotal;
      });
      return { chart: { ...prevData.chart, datasets: newDatasets } };
    });
  };

  return (
    <div className="lg_card_container row-span-2 relative">
      <div className="header">
        <p className="header_text">Sales Revenue</p>
        <ButtonGroup
          active={revenueType}
          setActive={setRevenueType}
          buttons={filterButtons}
        />
      </div>
      <div className="body">
        <div className="left_side_details">
          <div className="stats_details">
            {formattedSalesRevenueData.chart.topContainer.map((data, index) => {
              const hidden =
                formattedSalesRevenueData.chart.datasets[index - 1]?.hidden;
              return (
                <div key={data.legend}>
                  <p
                    className={`head_text cursor-pointer ${hidden ? '!text-gray-600' : ''}`}
                    onClick={() => toggleDataset(index - 1)}>
                    <span
                      className="indicator"
                      style={{ background: data.borderColor }}
                    />
                    {data.legend}
                  </p>
                  <p className="sub_text">$ {UsDollarFormatter(data.total)}</p>
                </div>
              );
            })}
          </div>
        </div>
        <div className="chart_wrapper">
          <Line
            options={filledLineChartoptions(
              formattedSalesRevenueData.chart.labels,
              '$',
            )}
            data={formattedSalesRevenueData.chart}
            className="chart"
          />
        </div>
      </div>
      <Loader loading={isLoading} loaderClassName="relative" />
    </div>
  );
};

export default SalesRevenue;
