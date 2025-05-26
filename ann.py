import math
import random
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
        self.learning_rate = 0.5

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
        # Menghitung nilai error pada output layer
        output_errors = [0 for _ in range(self.output_nodes)]
        for i in range(self.output_nodes):
            error = targets[i] - outputs[i]
            output_errors[i] = error * sigmoid_derivative(outputs[i])

        # Menghitung nilai error pada hidden layer
        hidden_errors = [0 for _ in range(self.hidden_nodes)]
        for i in range(self.hidden_nodes):
            error = 0
            for j in range(self.output_nodes):
                error += output_errors[j] * self.weights_ho[j][i]
            hidden_errors[i] = error * sigmoid_derivative(hidden[i])

        # Memperbarui bobot dan bias
        # Hidden ke output
        for i in range(self.output_nodes):
            for j in range(self.hidden_nodes):
                self.weights_ho[i][j] += self.learning_rate * output_errors[i] * hidden[j]
            self.bias_o[i] += self.learning_rate * output_errors[i]

        # Input ke hidden
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

def split_dataset(dataset, train_ratio=0.8):
    random.shuffle(dataset)
    split_point = int(len(dataset) * train_ratio)
    return dataset[:split_point], dataset[split_point:]
# Fungsi utama
def main():
    input_data = [0,0,0,0,0.2,0.4,0.3,0.1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
    # input_data = json.loads(sys.stdin.read())

    # dataset = read_dataset('/var/www/spk.warlocdev.my.id/public_html/master-cabai.csv')
    dataset = read_dataset('/Users/admin/Documents/Project/Web/Laravel/backend-spk-cabai/dataset-cabai.csv')
    train_set, test_set = split_dataset(dataset, train_ratio=0.8)

    classes = sorted(list(set(data[1] for data in dataset)))

    # Inisialisasi neural network
    input_nodes = len(dataset[0][0])
    hidden_nodes = 20
    output_nodes = len(classes)
    nn = NeuralNetwork(input_nodes, hidden_nodes, output_nodes)

    # Latih model
    max_epochs = 500
    threshold_error = 0.01
    for epoch in range(max_epochs):
        total_error = 0
        for features, label in train_set:
            targets = one_hot_encode(label, classes)

            outputs = nn.feedforward(features)
            error = sum((targets[i] - outputs[i]) ** 2 for i in range(len(targets))) / len(targets)
            total_error += error
            nn.train(features, targets)

        # avg_error = total_error / len(train_set)

        # if avg_error < threshold_error:
        #     print(f"✅ Training stopped at epoch {epoch+1} with average error {avg_error:.2f}")
        #     break

    # Uji model
    correct = 0
    total = len(test_set)
    for features, label in test_set:
        output = nn.feedforward(features)
        predicted_class = classes[output.index(max(output))]

        if predicted_class == label:
            correct += 1

    accuracy = correct / total
    # Contoh prediksi
    sample_input =input_data

    output = nn.feedforward(sample_input)

    predicted_class = classes[output.index(max(output))]
    # print(f"Prediksi untuk input {sample_input}: {predicted_class}")


    print(json.dumps({"result": predicted_class,"accuracy":accuracy*100}))

if __name__ == "__main__":
    main()
