import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder
from sklearn.neural_network import MLPClassifier
from sklearn.metrics import accuracy_score

# Load dataset
df = pd.read_csv("/Users/admin/Documents/Project/Web/Laravel/backend-spk-cabai/master-cabai-new.csv")

# Features and labels
X = df.iloc[:, :-1].values
y = df.iloc[:, -1].values

# Encode class labels
label_encoder = LabelEncoder()
y_encoded = label_encoder.fit_transform(y)

# Split dataset
X_train, X_test, y_train, y_test = train_test_split(X, y_encoded, test_size=0.2, random_state=42)

# Define ANN with sigmoid and backprop
model = MLPClassifier(
    hidden_layer_sizes=(17,),
    activation='logistic',
    solver='sgd',
    learning_rate_init=0.2,
    max_iter=500,
    random_state=42
)

# Train model
model.fit(X_train, y_train)

# Predict
y_pred = model.predict(X_test)

# Accuracy
accuracy = accuracy_score(y_test, y_pred)
print(f"Accuracy: {accuracy * 100:.2f}%")

# Predict sample
sample = [[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]]  # Replace with 27 features
pred = model.predict(sample)
print("Predicted class:", label_encoder.inverse_transform(pred)[0])
