export interface BookPrice {
  cost: number;
  xpReward: number;
  coinReward: number;
}

export const bookPrices: Record<number, BookPrice> = {
  1: { cost: 2300, xpReward: 150, coinReward: 300 },
  2: { cost: 1800, xpReward: 120, coinReward: 250 },
  3: { cost: 1500, xpReward: 100, coinReward: 200 },
  4: { cost: 2100, xpReward: 140, coinReward: 280 },
  5: { cost: 900, xpReward: 80, coinReward: 150 },
  6: { cost: 1200, xpReward: 90, coinReward: 180 },
  7: { cost: 2500, xpReward: 160, coinReward: 320 },
  8: { cost: 1600, xpReward: 110, coinReward: 220 },
  9: { cost: 2000, xpReward: 130, coinReward: 260 },
  10: { cost: 1400, xpReward: 95, coinReward: 190 },
  11: { cost: 1700, xpReward: 115, coinReward: 230 },
  12: { cost: 800, xpReward: 70, coinReward: 140 },
  13: { cost: 1100, xpReward: 85, coinReward: 170 },
  14: { cost: 2400, xpReward: 155, coinReward: 310 },
  15: { cost: 1900, xpReward: 125, coinReward: 240 },
  16: { cost: 2200, xpReward: 145, coinReward: 290 },
};
