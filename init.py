def read_dataset(filename):
    dataset = []
    with open(filename, newline='') as file:
        for line in file:
            values = line.strip().split(',')
            features = [float(x.lstrip('\ufeff')) for x in values[:-1]]
            label = values[-1]
            dataset.append((features, label))
    return dataset

def one_hot_encode(label, classes):
    encoding = [0] * len(classes)
    encoding[classes.index(label)] = 1
    return encoding

dataset = read_dataset('/Users/admin/Documents/Project/Web/Laravel/backend-spk-cabai/master-cabai-dataset.csv')
classes = sorted(list(set(data[1] for data in dataset)))
