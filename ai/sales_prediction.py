import sys
import json
import pandas as pd
import numpy as np
from sklearn.linear_model import LinearRegression

# Read JSON input
data = json.loads(sys.argv[1])

df = pd.DataFrame(data)

if df.empty:
    print(json.dumps({
        "next_30_days_total": 0,
        "daily_prediction_avg": 0
    }))
    sys.exit(0)

df['sale_date'] = pd.to_datetime(df['sale_date'])
df['day'] = (df['sale_date'] - df['sale_date'].min()).dt.days

X = df[['day']]
y = df['grand_total']

model = LinearRegression()
model.fit(X, y)

future_days = np.arange(df['day'].max()+1, df['day'].max()+31).reshape(-1, 1)
future_sales = model.predict(future_days)

result = {
    "next_30_days_total": round(float(future_sales.sum()), 2),
    "daily_prediction_avg": round(float(future_sales.mean()), 2)
}

print(json.dumps(result))
