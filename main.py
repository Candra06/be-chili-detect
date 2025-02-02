import math
import random
import csv
import sys
import json
# Fungsi aktivasi (sigmoid)
def sigmoid(x):
    return 1 / (1 + math.exp(-x))

# Turunan fungsi sigmoid
def sigmoid_derivative(x):
    return x * (1 - x)

# Kelas untuk Neural Network
class NeuralNetwork:
    def __init__(self, input_nodes, hidden_nodes, output_nodes):
        self.input_nodes = input_nodes
        self.hidden_nodes = hidden_nodes
        self.output_nodes = output_nodes

        # Inisialisasi bobot
        self.weights_ih = [[random.random() for _ in range(input_nodes)] for _ in range(hidden_nodes)]
        self.weights_ho = [[random.random() for _ in range(hidden_nodes)] for _ in range(output_nodes)]

        # Inisialisasi bias
        self.bias_h = [random.random() for _ in range(hidden_nodes)]
        self.bias_o = [random.random() for _ in range(output_nodes)]

        # Learning rate
        self.learning_rate = 0.2

    def feedforward(self, inputs):
        # Hidden layer
        hidden = [0 for _ in range(self.hidden_nodes)]
        for i in range(self.hidden_nodes):
            sum = 0
            for j in range(self.input_nodes):
                sum += inputs[j] * self.weights_ih[i][j]
            hidden[i] = sigmoid(sum + self.bias_h[i])

        # Output layer
        output = [0 for _ in range(self.output_nodes)]
        for i in range(self.output_nodes):
            sum = 0
            for j in range(self.hidden_nodes):
                sum += hidden[j] * self.weights_ho[i][j]
            output[i] = sigmoid(sum + self.bias_o[i])

        return output

    def train(self, inputs, targets):
        # Feedforward
        hidden = [0 for _ in range(self.hidden_nodes)]
        for i in range(self.hidden_nodes):
            sum = 0
            for j in range(self.input_nodes):
                sum += inputs[j] * self.weights_ih[i][j]
            hidden[i] = sigmoid(sum + self.bias_h[i])

        outputs = [0 for _ in range(self.output_nodes)]
        for i in range(self.output_nodes):
            sum = 0
            for j in range(self.hidden_nodes):
                sum += hidden[j] * self.weights_ho[i][j]
            outputs[i] = sigmoid(sum + self.bias_o[i])

        # Backpropagation
        # Calculate output layer errors
        output_errors = [0 for _ in range(self.output_nodes)]
        for i in range(self.output_nodes):
            error = targets[i] - outputs[i]
            output_errors[i] = error * sigmoid_derivative(outputs[i])

        # Calculate hidden layer errors
        hidden_errors = [0 for _ in range(self.hidden_nodes)]
        for i in range(self.hidden_nodes):
            error = 0
            for j in range(self.output_nodes):
                error += output_errors[j] * self.weights_ho[j][i]
            hidden_errors[i] = error * sigmoid_derivative(hidden[i])

        # Update weights and biases
        # Hidden to output
        for i in range(self.output_nodes):
            for j in range(self.hidden_nodes):
                self.weights_ho[i][j] += self.learning_rate * output_errors[i] * hidden[j]
            self.bias_o[i] += self.learning_rate * output_errors[i]

        # Input to hidden
        for i in range(self.hidden_nodes):
            for j in range(self.input_nodes):
                self.weights_ih[i][j] += self.learning_rate * hidden_errors[i] * inputs[j]
            self.bias_h[i] += self.learning_rate * hidden_errors[i]

# Fungsi untuk membaca dataset
def read_dataset(filename):
    dataset = []
    with open(filename, newline='') as file:
        for line in file:
            values = line.strip().split(',')
            features = [float(x.lstrip('\ufeff')) for x in values[:-1]]
            label = values[-1]
            dataset.append((features, label))
    return dataset

# Fungsi untuk mengubah label menjadi one-hot encoding
def one_hot_encode(label, classes):
    encoding = [0] * len(classes)
    encoding[classes.index(label)] = 1
    return encoding

# Fungsi utama
def main():
    input_data = json.loads(sys.stdin.read())
    # Baca dataset
    dataset = read_dataset('/Users/admin/Documents/Project/Web/Laravel/backend-spk-cabai/master-cabai.csv')

    # Dapatkan daftar kelas unik
    classes = list(set(data[1] for data in dataset))

    # Inisialisasi neural network
    input_nodes = len(dataset[0][0])
    hidden_nodes = 17
    output_nodes = len(classes)
    nn = NeuralNetwork(input_nodes, hidden_nodes, output_nodes)

    # Latih model
    epochs = 1000
    for _ in range(epochs):
        for features, label in dataset:
            targets = one_hot_encode(label, classes)
            # print(features)
            nn.train(features, targets)

    # Uji model
    correct = 0
    total = len(dataset)
    for features, label in dataset:
        output = nn.feedforward(features)
        predicted_class = classes[output.index(max(output))]

        if predicted_class == label:
            correct += 1

    accuracy = correct / total
    # print(f"correct: {correct}%")
    # print(f"Akurasi: {accuracy * 100:.2f}%")

    # Contoh prediksi
    sample_input =input_data['numbers']
    # sample_input = dataset[4][0]

    output = nn.feedforward(sample_input)
    predicted_class = classes[output.index(max(output))]
    # print(f"Prediksi untuk input {sample_input}: {predicted_class}")


    print(json.dumps({"result": predicted_class,"accuracy":accuracy*100}))

if __name__ == "__main__":
    main()
