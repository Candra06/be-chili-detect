<?php

class NeuralNetworkModel {
    public $inputNodes;
    public $hiddenNodes;
    public $outputNodes;
    public $weightInHid;
    public $weightHidOut;
    public $biasHide;
    public $biasOut;
    public $learningRate;
    public $epoch;

    public function __construct($inputNodes, $hiddenNodes, $outputNodes) {
        $this->inputNodes = $inputNodes;
        $this->hiddenNodes = $hiddenNodes;
        $this->outputNodes = $outputNodes;

        // generate bobot dan bias secara random
        $this->weightInHid = array_fill(0, $hiddenNodes, array_fill(0, $inputNodes, random_float(0,1)));
        $this->weightHidOut = array_fill(0, $outputNodes, array_fill(0, $hiddenNodes, random_float(0,1)));
        $this->biasHide = array_fill(0, $hiddenNodes, random_float(0,1));
        $this->biasOut = array_fill(0, $outputNodes, random_float(0,1));
        $this->learningRate = 0.2;
        $this->epoch = 1000;
    }

    function random_float($min, $max) {
        return $min + ($max - $min) * (mt_rand() / mt_getrandmax());
    }

    private function sigmoid($param) {
        return 1 / (1+ exp(-$param));
    }

    private function sigmoidBiner($param) {
        return $param * (1 - $param);
    }

    public function feedforward($inputs) {
        // Hidden layer
        $hidden = array_fill(0, $this->hiddenNodes, 0);
        for ($i = 0; $i < $this->hiddenNodes; $i++) {
            $sum = 0;
            for ($j = 0; $j < $this->inputNodes; $j++) {
                $sum += $inputs[$j] * $this->weightInHid[$i][$j];
            }
            $hidden[$i] = $this->sigmoid($sum + $this->biasHide[$i]);
        }

        // Output layer
        $output = array_fill(0, $this->outputNodes, 0);
        for ($i = 0; $i < $this->outputNodes; $i++) {
            $sum = 0;
            for ($j = 0; $j < $this->hiddenNodes; $j++) {
                $sum += $hidden[$j] * $this->weightHidOut[$i][$j];
            }
            $output[$i] = $this->sigmoid($sum + $this->biasOut[$i]);
        }

        return $output;
    }

    public function train($inputs, $targets) {
        // Feedforward
        $hidden = array_fill(0, $this->hiddenNodes, 0);
        for ($i = 0; $i < $this->hiddenNodes; $i++) {
            $sum = 0;
            for ($j = 0; $j < $this->inputNodes; $j++) {
                $sum += $inputs[$j] * $this->weightInHid[$i][$j];
            }
            $hidden[$i] = $this->sigmoid($sum + $this->biasHide[$i]);
        }

        $outputs = array_fill(0, $this->outputNodes, 0);
        for ($i = 0; $i < $this->outputNodes; $i++) {
            $sum = 0;
            for ($j = 0; $j < $this->hiddenNodes; $j++) {
                $sum += $hidden[$j] * $this->weightHidOut[$i][$j];
            }
            $outputs[$i] = $this->sigmoid($sum + $this->biasOut[$i]);
        }

        // Backpropagation
        // Output layer error
        $output_errors = array_fill(0, $this->outputNodes, 0);
        for ($i = 0; $i < $this->outputNodes; $i++) {
            $error = $targets[$i] - $outputs[$i];
            $output_errors[$i] = $error * $this->sigmoidDerivative($outputs[$i]);
        }

        // Hidden layer error
        $hidden_errors = array_fill(0, $this->hiddenNodes, 0);
        for ($i = 0; $i < $this->hiddenNodes; $i++) {
            $error = 0;
            for ($j = 0; $j < $this->outputNodes; $j++) {
                $error += $output_errors[$j] * $this->weightHidOut[$j][$i];
            }
            $hidden_errors[$i] = $error * $this->sigmoidDerivative($hidden[$i]);
        }

        // Update weights and biases
        // Hidden to output
        for ($i = 0; $i < $this->outputNodes; $i++) {
            for ($j = 0; $j < $this->hiddenNodes; $j++) {
                $this->weightHidOut[$i][$j] += $this->learningRate * $output_errors[$i] * $hidden[$j];
            }
            $this->biasOut[$i] += $this->learningRate * $output_errors[$i];
        }

        // Input to hidden
        for ($i = 0; $i < $this->hiddenNodes; $i++) {
            for ($j = 0; $j < $this->inputNodes; $j++) {
                $this->weightInHid[$i][$j] += $this->learningRate * $hidden_errors[$i] * $inputs[$j];
            }
            $this->biasHide[$i] += $this->learningRate * $hidden_errors[$i];
        }
    }
}

// Fungsi untuk membaca dataset
function readDataset($filename) {
    $dataset = [];
    $file = fopen($filename, "r");
    while (($line = fgetcsv($file)) !== false) {
        $features = array_map('floatval', array_slice($line, 0, -1));
        $label = end($line);
        $dataset[] = [$features, $label];
    }
    fclose($file);
    return $dataset;
}

// Fungsi untuk one-hot encoding label
function oneHotEncode($label, $classes) {
    $encoding = array_fill(0, count($classes), 0);
    $encoding[array_search($label, $classes)] = 1;
    return $encoding;
}

// Main function
function main() {
    $dataset = readDataset("/Users/admin/Documents/Kuliah/Script See/Skripsi/ANN/master-cabai.csv");

    // Ambil daftar kelas unik
    $classes = array_unique(array_column($dataset, 1));

    // Inisialisasi neural network
    $input_nodes = count($dataset[0][0]);
    $hidden_nodes = 17;
    $output_nodes = count($classes);
    $nn = new NeuralNetworkModel($input_nodes, $hidden_nodes, $output_nodes);

    // Latih model
    $epochs = 1000;
    for ($e = 0; $e < $epochs; $e++) {
        foreach ($dataset as $data) {
            list($features, $label) = $data;
            $targets = oneHotEncode($label, $classes);
            $nn->train($features, $targets);
        }
    }

    // Uji model
    $correct = 0;
    $total = count($dataset);
    foreach ($dataset as $data) {
        list($features, $label) = $data;
        $output = $nn->feedforward($features);
        $predicted_class = $classes[array_search(max($output), $output)];
        if ($predicted_class === $label) {
            $correct++;
        }
    }

    $accuracy = $correct / $total;
    echo "Correct: $correct\n";
    echo "Akurasi: " . round($accuracy * 100, 2) . "%\n";
}
